<template>
    <div>
        <div class="ui top fixed menu inverted navbar">
            <a class="item" @click="$router.push('/pineapple')">中原資管投票管理後臺</a>
            <div class="right menu">
                <a class="item font-style">{{ this.username }} 您好</a>
                <a class="item font-style" v-if="token" @click="logout">登出</a>
            </div>
        </div>

        <div class="ui grid cycuvote-container">
            <div class="three wide column">
                <!-- MENU -->
                <div class="ui c vertical menu">
                    <a class="item" @click="$router.push('/pineapple')"><i class="icon plus"></i>新增</a>
                    <a class="active item" @click="$router.push('/pineapple/activity')"><i class="icon tasks"></i>列表</a>
                </div>
                <!-- MENU_END -->
            </div>
            <div class="thirteen wide column">
                <!-- CARD -->
                <div class="ui stackable three column grid">
                    <div class="column" v-for="(item, index) in items" :key="index">
                        <div class="ui cards stackable">
                            <div class="card pointer" @click="$router.push({path:'groups/create/'+ item._id})">
                                <div class="content center aligned">
                                    {{ item.title }}
                                </div>
                                <div class="image">
                                    <img v-bind:src="item.img" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CARD_END -->
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                items: [],
                username: '',
                token: ''
            }
        },
        methods: {
            logout: function () {
                axios.get('//127.0.0.1:8000/pineapple/logout')
                this.$router.go('/');
            }
        },
        mounted: function() {
            
            var self = this;
            var router = this.$router;

            axios.get('//127.0.0.1:8000/pineapple/activity/1/get').then(response => {this.items = response.data})
            
            axios.get('//127.0.0.1:8000/pineapple/login/status')
            .then(function (res) {
                
                self.token = res.data.token;
                self.username = res.data.username;
            });
        }
    }
</script>

