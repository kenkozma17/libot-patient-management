<script setup></script>
<template>
  <aside class="side-panel fixed top-0 bottom-0 bg-main-gray z-10"></aside>
</template>
<style lang="scss" scoped>
.side-panel {
  width: 16.5rem;
  max-width: 16.5rem;
  left: -100%;
  transition: 0.35s ease-in-out left;

  &--active {
    left: 0;
  }

  @media only screen and (min-width: 768px) {
    left: 0;
  }
}
</style>
