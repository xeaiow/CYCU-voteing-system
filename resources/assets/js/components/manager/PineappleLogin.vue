<template>
    <div>
        <div class="ui grid">
            <div class="sixteen wide column  margin-20">
                <img src="//im.cycu.edu.tw/wSite/public/LayoutDesign/1437644222883.png" alt="" class="ui image centered medium">
            </div>
        </div>
        <div class="ui stackable four column grid">
            <div class="column centered margin-20">
                <div class="ui form">
                    <div class="field">
                        <label>管理帳號</label>
                        <input type="text" v-model="username">
                    </div>
                    <div class="field">
                        <label>密碼</label>
                        <input type="password" v-model="password">
                    </div>
                    <button class="ui button basic" @click="login" type="button">登入</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SuiVue from 'semantic-ui-vue'
    import 'semantic-ui-css/semantic.min.css'
    export default {
        data: function () {
            return {
                username: '',
                password: ''
            }
        },
        methods: {
            login: function () {

                var username;
                var token;
                var level;
                var router = this.$router;
                var self = this;

                axios.post('/pineapple/login/handle', {
                    username: this.username,
                    password: this.password,
                })
                .then(function (response) {          

                    // 登入失敗
                    if (response.data == "")
                    {
                        swal.fire({
                            title: "驗證失敗！",
                            text: "請確認您的管理帳密。",
                            type: "error",
                            confirmButtonText: "好的",
                        });
                        return false;
                    }
                    swal.fire({
                        title: "驗證成功！",
                        text: "可以開始設定後臺了。",
                        type: "success",
                        confirmButtonText: "好的",
                    })
                    .then(function () {
                        
                         window.location.href = '/pineapple';
                    });   
                });
            },
        }
    }
</script>

