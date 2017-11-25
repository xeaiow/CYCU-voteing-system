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

        <div class="ui grid stackable cycuvote-container">
            
            <div class="eight wide column centered center aligned" v-if="!message">
                <div class="ui negative message">
                    <div class="header">
                        The page you were looking for doesn't exist.
                    </div>
                    <p>
                        You may have mistyped the address or the page may have moved.
                    </p>
                </div>
            </div>

            <div class="four wide column" v-if="message">
                <div class="ui card fluid">
                    <div class="image">
                        <img v-bind:src="items.img">
                    </div>
                    <div class="content center aligned">
                        <a class="header font-style">{{ items.groups }}</a>
                    </div>
                </div>
                <div class="ui vertical fluid menu">
                    <div class="ui segment basic">
                        <div class="ui list">
                            <div class="item">
                                <i class="user icon"></i>
                                <div class="content font-style">
                                    {{ activitys.permission }}
                                </div>
                            </div>
                            <div class="item">
                                <i class="calendar icon"></i>
                                <div class="content font-style">
                                    {{ activitys.started }} ~ {{ activitys.deadline }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ui bottom black attached button font-style" @click="inputItouch" v-if="voting == 0">投給他</div>
                <div class="ui bottom disabled attached button font-style" v-else>已投過</div>
            </div>
            <div class="twelve wide column" v-if="message">
                <div class="ui piled segment project-content-text">
                    {{ items.discription }}
                </div>
                <h4 class="ui horizontal divider header">
                    <i class="image icon"></i> 海報預覽
                </h4>
                <div class="ui fluid images">
                    <img v-for="(item, index) in items.photo" :key="index" v-bind:src="item" class="team-images" />
                </div>
            </div>
          
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {

            return {
                items: {},
                activitys: {},
                message: '',
                voting: '',
                verifResult:'',
                username: '',
                password: '',
                token: ''
            }
        },
        methods: {
            mess: function (text, type) {

                this.$swal(text, '', type)
            },
            logout: function () {
                axios.get('//127.0.0.1:8000/logout')
                this.$router.go('/');
            },
            inputItouch: function () {
                
                var selfRoute   = this.$route.params.id;
                var self        = this;
                
                self.$swal({

                    title: '將神聖一票投給這組？',
                    text: "送出後不可反悔，且不可重複投票。",
                    showCancelButton: true,
                    confirmButtonText: "確定",
                    cancelButtonText: "考慮"

                    }).then(function (res) {


                        axios.get('//127.0.0.1:8000/voting/' + selfRoute)
                        .then(function (res) {

                            if (res.data.status == true)
                            {
                                self.mess('已完成投票！', 'success');
                                self.voting = 1;
                                return false;
                            }
                            self.mess('投票失敗！', 'error');
                        });

                    });
            }     
        },
        mounted: function() {

            axios.get('//127.0.0.1:8000/group/info/' + this.$route.params.id).then(response => {this.items = response.data.info;this.voting = response.data.voting;this.message = response.data.status;console.log(response);})
            axios.get('//127.0.0.1:8000/activity/info/' + this.$route.params.id).then(response => {this.activitys = response.data})
            axios.get('//127.0.0.1:8000/login/status').then(response => {this.token = response.data.token;this.username = response.data.username})
        }
    }
</script>




