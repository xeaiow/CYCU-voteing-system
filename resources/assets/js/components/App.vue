<template>
    <div>
        <div class="ts padded heading slate banner">
            <div class="ts container">
                <span class="header">中原資管專題投票系統</span>
                <span class="description">擇你所愛，選你所擇</span>
            </div>
        </div>
        <transition name="fade">
            <router-view></router-view>
        </transition>
    </div>
</template>
<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity .3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
.banner {
	background-image: url('https://i.imgur.com/wSw1qXW.png') !important;
	color: #E9EAED !important;
}
</style>