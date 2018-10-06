<template>
    <div>

    <!-- 聚焦看板 -->
    <div class="ts center aligned attached very padded segment">
        <div class="ts container">
            <!-- 留白 -->
            <div class="ts hidden divider"></div>
            <!-- / 留白 -->

            <!-- 標題 -->
            <div class="ts massive header">
                {{ info.title }}
                <div class="sub header">
                    {{ info.description }}
                </div>
            </div>
            <!-- / 標題 -->

            <!-- 留白 -->
            <div class="ts hidden divider"></div>
            <!-- / 留白 -->

            <button class="ts button" @click="$router.push('/')">回活動列表</button>

            <!-- 留白 -->
            <div class="ts hidden divider"></div>
            <!-- / 留白 -->
        </div>
    </div>
    <!-- / 聚焦看板 -->

    <!-- 主要片段 -->
    <div class="ts center aligned attached vertically very padded secondary segment">
        <!-- 容器 -->
        <div class="ts container">
            <!-- 卡片群組 -->
            <div class="ts stackable three flatted cards">
                <!-- 單個卡片 -->
                <div class="ts card" v-for="(item, index) in items" :key="index" @click="$router.push({path:'/group/' + item._id})">
                    <div class="image">
                        <img v-bind:src="item.img">
                    </div>
                    <div class="left aligned content">
                        <div class="description">{{ item.groups }}</div>
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
                axios.get('//127.0.0.1:8000/logout')
                this.$router.go('/');
            }
        },
        mounted: function() {
            axios.get('//127.0.0.1:8000/groups/info/' + this.$route.params.id).then(response => {this.items = response.data.groups;this.info = response.data.activity;this.message = response.data.message;})
            axios.get('//127.0.0.1:8000/login/status').then(response => {this.token = response.data.token;this.username = response.data.username})
        }
    }
</script>



