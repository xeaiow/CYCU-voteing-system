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
                    <div class="ui info message">
                        <ul class="list">
                            <li>帳號及密碼與 iTouch 相同</li>
                            <li>本系統僅作為驗證身分用</li>
                        </ul>
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
                username: '',
                password: '',
                token: ''
            }
        },
        methods: {

            login: function () {

                var username;
                var token;
                var router = this.$router;
                var self = this;

                axios.post('//127.0.0.1:8000/login/handle', {
                    username: this.username,
                    password: this.password

                })
                .then(function (response) {          
                    
                    // 登入失敗
                    if (response.data.login_status != 1 && response.data.login_status != 2)
                    {
                        self.$swal({
                            title: "驗證失敗！",
                            text: "請確認您的 iTouch 帳密。",
                            type: "error",
                            confirmButtonText: "好的",
                        });
                        return false;
                    }

                    if (response.data.info !== undefined)
                    {
                        username = response.data.info.substr(3, 8);
                        axios.post('//127.0.0.1:8000/login/save', {
                            username: username
                        });
                    }
                    else
                    {
                        // 注入 token 跟學號資訊
                        token = response.data.token;
                        username = response.data.username;
                        
                        router.push('/');
                    }

                    self.$swal({
                        title: "驗證成功！",
                        text: "可以開始投票了。",
                        type: "success",
                        confirmButtonText: "好的",
                    });
                });
            },
            logout: function () {
                axios.get('//127.0.0.1:8000/logout')
                this.$router.go('/');
            }
        },
        mounted: function () {
            axios.get('//127.0.0.1:8000/login/status').then(response => {this.token = response.data.token;this.username = response.data.username})
        }
    }
</script>

