<template>
    <div>
        <h1>Simple Test Component</h1>
        <p>If you can see this, the Vue component is working!</p>
        <p>Debug message: {{ debug }}</p>
        <p>Timestamp: {{ timestamp }}</p>
    </div>
</template>

<script>
export default {
    name: 'SimpleTest',
    props: {
        debug: String,
        timestamp: String
    }
};
</script>
