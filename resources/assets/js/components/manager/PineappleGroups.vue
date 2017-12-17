<template>
    <div>

        <div class="ui top fixed menu inverted">
            <a class="item" @click="$router.push('/pineapple')">中原資管投票管理後臺</a>
            <div class="right menu">
                <a class="item font-style">{{ this.username }} 您好</a>
                <a class="item font-style" v-if="token" @click="logout">登出</a>
            </div>
        </div>

        <div class="ui grid cycuvote-container">
            <div class="eight wide column">
                
                <h2 class="ui header">
                    <i class="inbox icon"></i>
                    <div class="content">組別資料</div>
                </h2>
                <div class="ui segment">

                    <img :src="info.img" class="ui image rounded medium centered" />
                    
                    <div class="ui form field">
                        <label>名稱</label>
                        <input type="text" v-model="info.groups" />
                    </div>


                    <div class="ui form field margin-20">
                        <label>描述</label>
                        <textarea v-model="info.description"></textarea>
                    </div>

                    <div class="ui large header">活動時間</div>
                    <img v-img:name v-for="(item, index) in info.photo" :key="index" v-bind:src="item" class="ui image rounded medium centered team-images" />
                    
                    <button class="ui button green fluid margin-20" @click="edit">更新</button>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="eight wide column">
                    
                    <h2 class="ui header">
                        <i class="users icon"></i>
                        <div class="content">
                            所有組別 <a v-bind:href="'/pineapple/groups/create/' + other_groups[0].activity">[進入此活動]</a>
                        </div>
                    </h2>

                    <div class="ui segment">
                        <div class="ui list">
                            <div class="item" v-for="(item, index) in other_groups" :key="index" @click="$router.push('/pineapple/groups/' + item._id)">
                                <div class="content"><a v-bind:href="'/pineapple/groups/' + item._id">{{ item.groups }}</a></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <!-- RIGHT_END -->

        </div>

    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                username: '',
                token: '',
                info: [],
                other_groups: []
            }
        },
        methods: {
            logout: function () {
                axios.get('//127.0.0.1:8000/pineapple/logout')
                this.$router.go('/');
            },
            edit: function () {

                var self = this;
                axios.post('//127.0.0.1:8000/pineapple/groups/update/set', {
                    id: this.$route.params.id,
                    groups: this.info.groups,
                    description: this.info.description
                })
                .then(function (res) {   

                    self.$swal({
                        title: "更新成功！",
                        text: "已更新為最新狀態。",
                        confirmButtonText: "知道了",
                    });
                });
                console.log(this.$route.params.id);
            }
        },
        mounted: function () {

            var self = this;
            var router  = this.$router;

            axios.get('//127.0.0.1:8000/pineapple/groups/' + this.$route.params.id + '/get')
            .then(function (response) {

                self.info = response.data;

                axios.get('//127.0.0.1:8000/pineapple/activity/groups/' + response.data.activity + '/get').then(response => {self.other_groups = response.data})
            });

            axios.get('//127.0.0.1:8000/pineapple/login/status')
            .then(function (res) {
                
                self.token = res.data.token;
                self.username = res.data.username;
            });
        }
    }
</script>

