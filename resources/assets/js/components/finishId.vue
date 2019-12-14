<template>
    <div>
         <!-- 標題板岩 -->
        <div class="ts padded heading slate">
            <div class="ts container">
                <span class="header">競賽結果</span>
                <span class="description">早安，我的朋友！無論成敗與否，我們都要勇敢面對</span>
            </div>
        </div>
        <!-- / 標題板岩 -->
        <!-- 窄容器網格系統 -->
        <div class="ts very padded relaxed stackable container grid">
            <!-- 欄位 -->
            <div class="sixteen wide column margin-navbar">
                <!-- 標題 -->
                <h1 class="ts center aligned header">
                    {{ info.title }}
                    <div class="sub header">
                        {{ info.description }}
                    </div>
                </h1>
                <!-- / 標題 -->

                <!-- 留白 -->
                <div class="ts hidden divider"></div>
                <!-- / 留白 -->
            </div>
            <!-- / 欄位 -->

            <!-- 便利貼欄位 -->
            <div class="sixteen wide column">
                <!-- 瀑布流卡片群組 -->
                <div class="ts stackable three cards">
                    <!-- 單張卡片 -->
                    <div class="ts card" v-for="(item, index) in items" :key="index">
                        <div class="content">
                            <div class="header">
                                <div class="ts grid">
                                    <div class="four wide column">
                                        <div class="ts positive statistic">
                                            <div class="value">{{ item.count }}</div>
                                            <div class="label">得票數</div>
                                        </div>
                                    </div>
                                    <div class="twelve wide column">
                                        <div class="header center aligned">
                                            <div class="ts borderless segment">
                                                {{ item.groups }}
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="image" @click="$router.push({path:'/team/' + item._id})">
                            <img v-bind:src="item.img">
                        </div>
                    </div>
                    <!-- / 單張卡片 -->
                </div>
                <!-- / 瀑布流卡片群組 -->
            </div>
            <!-- / 便利貼欄位 -->
        </div>
        <!-- / 窄容器網格系統 -->
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                items: [],
                info: [],
                message: '',
                username: '',
                password: '',
                token: ''
            }
        },
        methods: {
            logout: function () {
                axios.get('/logout')
                this.$router.go('/');
            }
        },
        mounted: function() {
            axios.get('/groups/top/' + this.$route.params.id).then(response => {this.items = response.data.groups;this.info = response.data.activity;this.message = response.data.message;})
            axios.get('/login/status').then(response => {this.token = response.data.token;this.username = response.data.username})
        }
    }
</script>



