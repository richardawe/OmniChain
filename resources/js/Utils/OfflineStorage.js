/**
 * Offline Storage Utility for OmniChain Driver App
 * Provides IndexedDB-based offline storage and sync capabilities
 */

class OfflineStorage {
    constructor() {
        this.dbName = 'OmniChainDriver'
        this.dbVersion = 1
        this.db = null
        this.isOnline = navigator.onLine
        this.syncQueue = []
        
        // Initialize database
        this.initDB()
        
        // Listen for online/offline events
        window.addEventListener('online', () => {
            this.isOnline = true
            this.syncOfflineData()
        })
        
        window.addEventListener('offline', () => {
            this.isOnline = false
        })
    }

    /**
     * Initialize IndexedDB database
     */
    async initDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion)
            
            request.onerror = () => {
                console.error('Failed to open IndexedDB:', request.error)
                reject(request.error)
            }
            
            request.onsuccess = () => {
                this.db = request.result
                console.log('IndexedDB initialized successfully')
                resolve(this.db)
            }
            
            request.onupgradeneeded = (event) => {
                const db = event.target.result
                
                // Create tasks store
                if (!db.objectStoreNames.contains('tasks')) {
                    const tasksStore = db.createObjectStore('tasks', { keyPath: 'id' })
                    tasksStore.createIndex('status', 'status', { unique: false })
                    tasksStore.createIndex('updated_at', 'updated_at', { unique: false })
                }
                
                // Create offline data store for sync queue
                if (!db.objectStoreNames.contains('offlineData')) {
                    const offlineStore = db.createObjectStore('offlineData', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    })
                    offlineStore.createIndex('type', 'type', { unique: false })
                    offlineStore.createIndex('timestamp', 'timestamp', { unique: false })
                }
                
                // Create location data store
                if (!db.objectStoreNames.contains('locationData')) {
                    const locationStore = db.createObjectStore('locationData', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    })
                    locationStore.createIndex('timestamp', 'timestamp', { unique: false })
                }
                
                // Create proof of delivery store
                if (!db.objectStoreNames.contains('proofOfDelivery')) {
                    const podStore = db.createObjectStore('proofOfDelivery', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    })
                    podStore.createIndex('task_id', 'task_id', { unique: false })
                    podStore.createIndex('timestamp', 'timestamp', { unique: false })
                }
                
                console.log('IndexedDB schema created')
            }
        })
    }

    /**
     * Store tasks offline
     */
    async storeTasks(tasks) {
        if (!this.db) await this.initDB()
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['tasks'], 'readwrite')
            const store = transaction.objectStore('tasks')
            
            // Clear existing tasks
            store.clear()
            
            // Add new tasks
            tasks.forEach(task => {
                store.add({
                    ...task,
                    offline_saved: true,
                    saved_at: new Date().toISOString()
                })
            })
            
            transaction.oncomplete = () => {
                console.log(`${tasks.length} tasks stored offline`)
                resolve()
            }
            
            transaction.onerror = () => {
                console.error('Failed to store tasks offline:', transaction.error)
                reject(transaction.error)
            }
        })
    }

    /**
     * Get tasks from offline storage
     */
    async getTasks() {
        if (!this.db) await this.initDB()
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['tasks'], 'readonly')
            const store = transaction.objectStore('tasks')
            const request = store.getAll()
            
            request.onsuccess = () => {
                resolve(request.result)
            }
            
            request.onerror = () => {
                console.error('Failed to get tasks from offline storage:', request.error)
                reject(request.error)
            }
        })
    }

    /**
     * Store location update offline
     */
    async storeLocationUpdate(locationData) {
        if (!this.db) await this.initDB()
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['locationData'], 'readwrite')
            const store = transaction.objectStore('locationData')
            
            store.add({
                ...locationData,
                timestamp: new Date().toISOString(),
                synced: false
            })
            
            transaction.oncomplete = () => {
                console.log('Location update stored offline')
                resolve()
            }
            
            transaction.onerror = () => {
                console.error('Failed to store location update:', transaction.error)
                reject(transaction.error)
            }
        })
    }

    /**
     * Store proof of delivery offline
     */
    async storeProofOfDelivery(taskId, podData) {
        if (!this.db) await this.initDB()
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['proofOfDelivery'], 'readwrite')
            const store = transaction.objectStore('proofOfDelivery')
            
            store.add({
                task_id: taskId,
                ...podData,
                timestamp: new Date().toISOString(),
                synced: false
            })
            
            transaction.oncomplete = () => {
                console.log('Proof of delivery stored offline')
                resolve()
            }
            
            transaction.onerror = () => {
                console.error('Failed to store proof of delivery:', transaction.error)
                reject(transaction.error)
            }
        })
    }

    /**
     * Add data to sync queue
     */
    async addToSyncQueue(type, data, url, method = 'POST') {
        if (!this.db) await this.initDB()
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['offlineData'], 'readwrite')
            const store = transaction.objectStore('offlineData')
            
            store.add({
                type,
                url,
                method,
                data: JSON.stringify(data),
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                timestamp: new Date().toISOString(),
                retry_count: 0
            })
            
            transaction.oncomplete = () => {
                console.log(`${type} added to sync queue`)
                resolve()
            }
            
            transaction.onerror = () => {
                console.error('Failed to add to sync queue:', transaction.error)
                reject(transaction.error)
            }
        })
    }

    /**
     * Sync offline data when back online
     */
    async syncOfflineData() {
        if (!this.isOnline || !this.db) return
        
        try {
            const offlineData = await this.getOfflineData()
            
            for (const item of offlineData) {
                try {
                    await this.syncItem(item)
                    await this.removeOfflineData(item.id)
                } catch (error) {
                    console.error('Failed to sync item:', error)
                    await this.incrementRetryCount(item.id)
                }
            }
            
            console.log('Offline data sync completed')
        } catch (error) {
            console.error('Sync failed:', error)
        }
    }

    /**
     * Get all offline data
     */
    async getOfflineData() {
        if (!this.db) await this.initDB()
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['offlineData'], 'readonly')
            const store = transaction.objectStore('offlineData')
            const request = store.getAll()
            
            request.onsuccess = () => {
                resolve(request.result)
            }
            
            request.onerror = () => {
                reject(request.error)
            }
        })
    }

    /**
     * Sync individual item
     */
    async syncItem(item) {
        const response = await fetch(item.url, {
            method: item.method,
            headers: item.headers,
            body: item.method !== 'GET' ? item.data : undefined
        })
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`)
        }
        
        return response.json()
    }

    /**
     * Remove item from offline data
     */
    async removeOfflineData(id) {
        if (!this.db) await this.initDB()
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['offlineData'], 'readwrite')
            const store = transaction.objectStore('offlineData')
            const request = store.delete(id)
            
            request.onsuccess = () => resolve()
            request.onerror = () => reject(request.error)
        })
    }

    /**
     * Increment retry count for failed sync
     */
    async incrementRetryCount(id) {
        if (!this.db) await this.initDB()
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['offlineData'], 'readwrite')
            const store = transaction.objectStore('offlineData')
            const getRequest = store.get(id)
            
            getRequest.onsuccess = () => {
                const item = getRequest.result
                if (item && item.retry_count < 3) {
                    item.retry_count++
                    store.put(item)
                } else if (item) {
                    // Remove item after 3 retries
                    store.delete(id)
                }
                resolve()
            }
            
            getRequest.onerror = () => reject(getRequest.error)
        })
    }

    /**
     * Get sync queue status
     */
    async getSyncQueueStatus() {
        const offlineData = await this.getOfflineData()
        return {
            pending: offlineData.length,
            items: offlineData.map(item => ({
                type: item.type,
                timestamp: item.timestamp,
                retry_count: item.retry_count
            }))
        }
    }

    /**
     * Clear all offline data
     */
    async clearAllOfflineData() {
        if (!this.db) await this.initDB()
        
        const stores = ['tasks', 'offlineData', 'locationData', 'proofOfDelivery']
        
        for (const storeName of stores) {
            await new Promise((resolve, reject) => {
                const transaction = this.db.transaction([storeName], 'readwrite')
                const store = transaction.objectStore(storeName)
                const request = store.clear()
                
                request.onsuccess = () => resolve()
                request.onerror = () => reject(request.error)
            })
        }
        
        console.log('All offline data cleared')
    }
}

// Create singleton instance
const offlineStorage = new OfflineStorage()

export default offlineStorage
