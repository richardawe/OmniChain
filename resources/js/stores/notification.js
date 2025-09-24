import { defineStore } from 'pinia'

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
        unreadCount: 0,
        loading: false,
        error: null
    }),

    getters: {
        unreadNotifications: (state) => state.notifications.filter(notification => !notification.read),
        notificationsByType: (state) => (type) => state.notifications.filter(notification => notification.type === type)
    },

    actions: {
        addNotification(notification) {
            const newNotification = {
                id: Date.now(),
                type: notification.type || 'info',
                title: notification.title,
                message: notification.message,
                read: false,
                timestamp: new Date(),
                ...notification
            }
            
            this.notifications.unshift(newNotification)
            this.unreadCount++
        },

        markAsRead(id) {
            const notification = this.notifications.find(n => n.id === id)
            if (notification && !notification.read) {
                notification.read = true
                this.unreadCount--
            }
        },

        markAllAsRead() {
            this.notifications.forEach(notification => {
                if (!notification.read) {
                    notification.read = true
                }
            })
            this.unreadCount = 0
        },

        removeNotification(id) {
            const notification = this.notifications.find(n => n.id === id)
            if (notification && !notification.read) {
                this.unreadCount--
            }
            this.notifications = this.notifications.filter(n => n.id !== id)
        },

        clearAll() {
            this.notifications = []
            this.unreadCount = 0
        },

        // Success notification
        success(title, message) {
            this.addNotification({
                type: 'success',
                title,
                message
            })
        },

        // Error notification
        error(title, message) {
            this.addNotification({
                type: 'error',
                title,
                message
            })
        },

        // Warning notification
        warning(title, message) {
            this.addNotification({
                type: 'warning',
                title,
                message
            })
        },

        // Info notification
        info(title, message) {
            this.addNotification({
                type: 'info',
                title,
                message
            })
        }
    }
})
