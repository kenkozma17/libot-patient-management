<script setup></script>
<template>
    <div class="grid md:grid-cols-3 gap-4 mb-4">
        <div>
            <slot name="col1"></slot>
        </div>
        <div>
            <slot name="col2"></slot>
        </div>
        <div>
            <slot name="col3"></slot>
        </div>
    </div>
</template>
