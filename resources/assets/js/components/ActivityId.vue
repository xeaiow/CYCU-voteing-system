<template>
    <div>

        <div class="ts secondary inverted negative message hover-pointer" v-if="errorPage" @click="$router.push('/');">
            <div class="header">嗚嗚怎麼沒有東西</div>
            <p>你欲拜訪的活動可能因時效過了而下架。</p>
        </div>

        <div v-else v-cloak>
            <!-- 頂部固定選單 -->
            <div class="ts large link attached inverted info menu" v-if="name != ''">
                <div class="ts container">
                    <div class="header item hover-pointer" @click="logout()">登出 {{ name }}</div>
                </div>
            </div>
            <!-- / 頂部固定選單 -->

            <!-- 主要片段 -->
            <div class="ts attached vertically basic">
                <!-- 容器 -->
                <div class="ts hidden divider"></div>
                <div class="ts container">
                    <div class="ts grid stackable">
                        <div class="two wide column left aligned">
                            <button class="ts button" @click="$router.push('/')"><i class="long arrow left icon"></i>活動列表</button>
                        </div>
                        <div class="fourteen wide column center aligned">
                            <div class="ts heading slate">
                            <h2 class="ts header">  
                                <div class="content">
                                    {{ info.title }}
                                    <div class="sub header">{{ info.description }}</div>
                                </div>
                            </h2>
                            </div>
                        </div>
                    </div>
                    <!-- 卡片群組 -->
                    <div class="ts stackable three flatted cards">
                        <!-- 單個卡片 -->
                        <div class="ts card focus" v-for="(item, index) in items" :key="index" @click="$router.push({path:'/team/' + item._id})">
                            <div class="image">
                                <img v-bind:src="item.img" />
                                <div class="header">
                                    {{ item.groups }}
                                </div>
                            </div>
                        </div>
                        <!-- / 單個卡片 -->
 
                    </div>
                    <!-- / 卡片群組 -->
                </div>
                <!-- / 容器 -->
            </div>
            <!-- / 主要片段 -->
        </div>
    </div>
</template>

<script>
    export default {
        metaInfo: {
            title: '108 學年度專題海報競賽',
            meta: [
                {
                    property: 'og:title',
                    content: '108 學年度專題海報競賽',
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
                items: [],
                info: [],
                name: '',
                errorPage: false
            }
        },
        methods: {
            logout: function () {
                sessionStorage.clear();
                this.name = '';
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: '已登出。',
                    showConfirmButton: false,
                    timer: 700
                });
            }
        },
        created: function() {

            if (sessionStorage.getItem('name') != null) {
                this.name = sessionStorage.getItem('name');
            }

            let self = this;

            axios.get('/groups/info/' + this.$route.params.id).then(response => {
                this.items = response.data.groups;
                this.info = response.data.activity;
            });
        },
    }
</script>



