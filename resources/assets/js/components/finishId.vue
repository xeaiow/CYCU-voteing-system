<template>
    <div>
        <div class="ui inverted menu cycuvote-theme cycuvote-menu fixed">
            <a class="active item font-style" @click="$router.push('/')">中原大學資訊管理學系投票系統</a>
            <a class="item font-style" @click="$router.push('/')">活動列表</a>
            <div class="right menu">
                <a class="item font-style" v-if="token">{{ this.username }} 您好</a>
                <a class="item font-style" v-if="token" @click="logout">登出</a>
                <a class="item font-style" v-if="!token" @click="$router.push('/login')">登入</a>
            </div>
        </div>

        <div class="ui grid cycuvote-container">

            <div class="eight wide column centered center aligned">
                <div class="ui negative message" v-if="message">
                    <div class="header">
                        The page you were looking for doesn't exist.
                    </div>
                    <p>
                        You may have mistyped the address or the page may have moved.
                    </p>
                </div>
            </div>

            <div v-if="!message">
                <div class="sixteen wide column">
                    <h2 class="ui icon header center aligned">
                        <i class="lab icon"></i>
                        <div class="content font-style">
                            {{ info.title }}
                        </div>
                        <div class="sub header">{{ info.description }}</div>
                    </h2>
                </div>

                <div class="sixteen wide column">

                    <div class="ui stackable three column grid">
                        <div class="column" v-for="(item, index) in items">
                            <div class="ui cards stackable">
                                <div class="card pointer" @click="$router.push({path:'/group/' + item._id})">
                                    <div class="content">
                                        <div class="ui top right attached label basic">{{ item.count }} 票</div>
                                        {{ item.groups }}
                                    </div>
                                    <div class="image">
                                        <img v-bind:src="item.img" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
            axios.get('//127.0.0.1:8000/groups/top/' + this.$route.params.id).then(response => {this.items = response.data.groups;this.info = response.data.activity;this.message = response.data.message;})
            axios.get('//127.0.0.1:8000/login/status').then(response => {this.token = response.data.token;this.username = response.data.username})
        }
    }
</script>



