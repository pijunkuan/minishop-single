<template>
  <div id="app">
    <keep-alive>
        <router-view v-if="$route.meta.keepAlive !== undefined && $route.meta.keepAlive" />
    </keep-alive>
    <router-view v-if="$route.meta.keepAlive === undefined || !$route.meta.keepAlive" />
  </div>
</template>

<script>

export default {
}
</script>

<style lang="scss">
@import './assets/style/style.scss';
</style>