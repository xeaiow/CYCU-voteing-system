<template>
    <div>
        <div class="ui top fixed menu inverted navbar">
            <a class="item" @click="$router.push('/voting/pineapple')">中原資管投票管理後臺</a>
            <div class="right menu">
                <a class="item font-style">{{ this.username }} 您好</a>
                <a class="item font-style" v-if="token" @click="logout">登出</a>
            </div>
        </div>

        <div class="ui grid cycuvote-container">
            <div class="thirteen wide column">
                
                <h2 class="ui header">
                    <i class="inbox icon"></i>
                    <div class="content">組別資料 <a v-bind:href="'/voting/pineapple/groups/create/' + other_groups[0].activity">[進入此活動]</a></div>
                </h2>
                <div class="ui segment">

                    <div class="sixteen wide column">
                        <button class="ui icon button black" @click="clickChangeCover">
                            <i class="folder open icon tiny"></i> 更換封面
                        </button>
                    </div>

                    <input type="file" id="changeCover" @change="changeCover" style="width:0px;" />
                    <div class="sixteen wide column">
                        <img v-img:name :src="info.img" class="ui image team-images rounded medium centered" />
                    </div>
                    

                    <div class="ui form field">
                        <label>名稱</label>
                        <input type="text" v-model="info.groups" />
                    </div>


                    <div class="ui form field margin-20">
                        <label>描述</label>
                        <textarea v-model="info.description"></textarea>
                    </div>

                    <div class="ui large header">海報</div>
                    <div class="sixteen wide column">
                        <input type="file" @change="onFileChange" id="select-img" multiple/>
                    </div>
                    <div class="ui small bordered images" v-for="(item, index) in info.photo" :key="index">
                        <div class="sixteen wide column right aligned">
                            <img v-img:name v-bind:src="item" class="ui image rounded medium centered team-images" />
                            <button class="circular ui icon button red" @click="removePhoto(index)">
                                <i class="trash alternate icon tiny"></i>
                            </button>
                        </div>
                    </div>
                    <button class="ui icon button fluid black" @click="uploadImage">上傳</button>

                    <button class="ui button black fluid margin-20" @click="edit">更新</button>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="three wide column">
                    
                    <h2 class="ui header">
                        <i class="users icon"></i>
                        <div class="content">
                            所有組別
                        </div>
                    </h2>

                    <div class="ui segment">
                        <div class="ui list">
                            <div class="item" v-for="(item, index) in other_groups" :key="index" @click="$router.push('/voting/pineapple/groups/' + item._id)">
                                <div class="content">
                                    <h2 class="ui header"><a v-bind:href="'/voting/pineapple/groups/' + item._id">{{ item.groups }}</a></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <!-- RIGHT_END -->
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
                axios.get('/voting/pineapple/logout')
                this.$router.go('/');
            },
            edit: function () {

                var self = this;
                axios.post('/voting/pineapple/groups/update/set', {
                    id: this.$route.params.id,
                    groups: this.info.groups,
                    img: this.info.img,
                    photo: this.info.photo,
                    description: this.info.description
                })
                .then(function (res) {   

                    self.$notify({
                        group: 'foo',
                        type: 'success',
                        title: '成功',
                        text: '已更新為最新狀態。'
                    });
                });
                console.log(this.$route.params.id);
            },
            clickChangeCover: function () {
               document.getElementById('changeCover').click();
            },
            changeCover: function (e) {

                var files = e.target.files || e.dataTransfer.files;
                
                if (!files.length) {
                    return;
                }
                this.coverImage(files[0]);

                this.$notify({
                    group: 'foo',
                    text: '上傳中...'
                });
            },
            coverImage: function (file) {

                var image  = new Image();
                var reader = new FileReader();
                var self   = this;

                reader.onload = (e) => {

                    self.info.img = e.target.result;

                    axios({
                        method: 'post',
                        url: '/voting/image/upload',
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        data: {
                            userImage: self.info.img,
                        }
                    })
                    .then(function (res) {

                        self.info.img = res.data[0].data.link;

                        self.$notify({
                            group: 'foo',
                            type: 'success',
                            title: '成功',
                            text: '已更換封面'
                        });
                    });
                };
                reader.readAsDataURL(file); 
            },
            removePhoto: function (i) {
                this.info.photo.splice(i, 1);
            },
            onFileChange: function (e)
            {
                
                var files = e.target.files || e.dataTransfer.files;
                var self = this;

                var image = new Image();
                var reader = new FileReader();

                if (!files.length) {
                    return;
                }
                for (var i = 0; i < files.length; i++) {
                    self.createImage(files[i]);
                }

                // this.image = [];
            },
            createImage(file) {
                
                var image = new Image();
                var reader = new FileReader();

                reader.onload = (e) => {
                    this.info.photo.push(e.target.result);
                };
                reader.readAsDataURL(file);
            },
            uploadImage: function () {

                this.$notify({
                    group: 'foo',
                    title: '上傳中',
                    text: '請耐心等待上傳'
                });

                var self = this;
                axios({
                    method: 'post',
                    url: '/voting/image/upload',
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    data: {
                        userImage: this.info.photo,
                    }
                })
                .then(function (res) {
                    
                    var data = res.data;
                    self.info.photo = [];
                    for (var i =0; i< data.length; i++) {
                        
                        self.info.photo.push(data[i].data.link);
                    }
                    
                    self.$swal({
                        title: "上傳成功！",
                        text: "輸入相關資料後，點擊送出完成新增。",
                        confirmButtonText: "知道了",
                    });

                });
            },
        },
        mounted: function () {

            var self = this;
            var router  = this.$router;

            axios.get('/voting/pineapple/groups/' + this.$route.params.id + '/get')
            .then(function (response) {

                self.info = response.data;

                axios.get('/voting/pineapple/activity/groups/' + response.data.activity + '/get').then(response => {self.other_groups = response.data})
            });

            axios.get('/voting/pineapple/login/status')
            .then(function (res) {
                
                self.token = res.data.token;
                self.username = res.data.username;
            });
        }
    }
</script>

