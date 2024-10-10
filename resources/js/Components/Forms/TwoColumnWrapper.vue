<script setup></script>
<template>
    <div class="grid md:grid-cols-2 gap-4 md:mb-4 mb-2">
        <div>
            <slot name="col1"></slot>
        </div>
        <div>
            <slot name="col2"></slot>
        </div>
    </div>
</template>
