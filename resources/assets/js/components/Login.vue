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

        <div class="ui stackable four column grid">
            <div class="column centered margin-80">
                <div class="ui form">
                    <div class="field">
                        <label>iTouch 帳號</label>
                        <input type="text" v-model="username">
                    </div>
                    <div class="field">
                        <label>密碼</label>
                        <input type="password" v-model="password">
                    </div>
                    <button class="ui button basic" type="button" @click="login">登入</button>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {

        data: function () {
            return {
                username: '',
                password: '',
                token: ''
            }
        },
        methods: {

            login: function () {

                axios.post('//127.0.0.1:8000/login/handle', {
                    username: this.username,
                    password: this.password
                })
                .then(response => {
                    this.token = response.data.token;
                    this.username = response.data.username;
                    this.$router.push('/');
                });
            },
            logout: function () {
             
                this.$router.go('/');
            }
        },
        mounted: function () {
            axios.get('//127.0.0.1:8000/login/status').then(response => {this.token = response.data.token;this.username = response.data.username})
        }
    }
</script>

