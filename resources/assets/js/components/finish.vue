<template>

    <div>

        <!-- 主要容器 -->
        <div class="ts padded container">

            <div class="ts hidden divider"></div>
                
            <div class="ts secondary menu">
                <a class="item" @click="$router.push('/')">活動列表</a>
                <div class="header item" @click="$router.push('/done')">公佈欄</div>
            </div>

            <!-- 活動卡片群組 -->
            <div class="ts three cards">
                <div class="ts card focus" v-for="(item, index) in items" :key="index" @click="$router.push({path: '/done/' + item._id})">
                    <div class="image">
                        <img v-bind:src="item.img">
                    </div>
                    <div class="content">
                        <div class="header center aligned">
                            {{ item.title }}
                        </div>
                        
                    </div>
                    <div class="secondary extra content">
                        <!-- 資訊 -->
                        <div class="ts three column grid">
                            <div class="column">
                                <strong>20</strong>
                                <br>
                                參賽組
                            </div>
                            <div class="column">
                                <strong>2018/10/6~2018/10/10</strong>
                                <br>
                                競賽時間
                            </div>
                        </div>
                        <!-- / 資訊 -->
                    </div>
                </div>
            </div>
            <!-- / 活動卡片群組 -->
        </div>
    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                items:[],
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
        created: function () {
            axios.get('/finished_activity/get').then(response => 
                this.items = response.data
            )
            axios.get('/login/status').then(response => {
                this.token = response.data.token;
                this.username = response.data.username
            })
        }
    }
</script>

