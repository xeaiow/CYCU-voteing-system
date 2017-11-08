<template>
    <div>
        <div class="ui inverted menu cycuvote-theme cycuvote-menu fixed">
            <router-link class="active item font-style" :to="{ path:'/' }">
                中原大學資訊管理學系投票系統
            </router-link>
            <div class="item font-style">
                <router-link :to="{ path:'/' }" exact>活動列表</router-link>
            </div>
            <div class="right menu">
                <router-link class="item font-style" :to="{ path:'/' }" exact>登入</router-link>
            </div>
        </div>
    </div>
</template>