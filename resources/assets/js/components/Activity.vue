<template>
    <div>
        <div class="ts padded container">
            <div class="ts hidden divider"></div>
            <div class="ts secondary menu">
                <div class="header item" @click="$router.push('/')">活動列表</div>
                <a class="item" @click="$router.push('/done')">公佈欄</a>
            </div>

            <!-- 活動卡片群組 -->
            <div class="ts three stackable cards">
                <div class="ts card focus" @click="$router.push({path:`/activity/${item._id}`})" v-for="(item, index) in items" :key="index">
                    <div class="image">
                        <img v-bind:src="item.img">
                    </div>
                    <div class="content">
                        <div class="header">
                            <h3 class="ts center aligned header">{{ item.title }}</h3>
                        </div>
                        <div class="description">
                            {{ item.description }}
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
        <!-- / 主要容器 -->


    </div>
</template>

<script>
    export default {
        metaInfo: {
            title: '中原大學資訊管理學系投票系統',
            meta: [
                {
                    property: 'og:title',
                    content: '中原大學資訊管理學系投票系統',
                    vmid: 'og:title'
                },
                {
                    property: 'og:url',
                    content: window.location.href,
                    vmid: 'og:url'
                },
                {
                    property: 'og:image',
                    content: 'https://i.imgur.com/OjrlSUW.png',
                    vmid: 'og:image'
                },
                {
                    property: 'og:description',
                    content: '擇你所愛，選你所擇',
                    vmid: 'og:description'
                }
            ]
        },
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
                axios.get('/logout').then(response => 
                    this.$router.go('/')
                )
            }
        },
        created: function () {
            axios.get('/activity/get').then(response => 
                this.items = response.data
            )

            axios.get('/login/status').then(response => {
                this.token = response.data.token
                this.username = response.data.username
                this.level = response.data.level
            })
        }
    }
</script>


