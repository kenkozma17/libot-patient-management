<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type"
    class="inline-flex items-center px-14 py-3 bg-dark-green border border-transparent
     rounded-md font-semibold text-xs text-white uppercase tracking-widest
     hover:bg-dark-green/[0.8] focus:bg-dark-green/[0.9] active:bg-dark-green/[0.9]
     focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out
    duration-150">
        <slot />
    </button>
</template>
