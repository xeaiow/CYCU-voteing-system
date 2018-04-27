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

            <!-- LEFT -->
            <div class="eight wide column">
                <h2 class="ui header">
                    <i class="inbox icon"></i>
                    <div class="content">活動資料</div>
                </h2>
                <div class="ui segment" v-if="!edit_open">

                    <div class="ui grid">
                        <div class="fourteen wide column">
                            <h2 class="ui icon header">名稱：{{ info.title }}</h2>
                        </div>
                        <div class="two wide column">
                            <button class="circular ui icon button green" @click="edit"><i class="icon edit"></i></button>
                        </div>
                    </div>

                    <img :src="info.img" class="ui image rounded medium centered" />

                    <div class="ui large header">描述</div>
                    <p>{{ info.description }}</p>

                    <div class="ui large header">活動時間</div>
                    <p>{{ info.started }} ~ {{ info.deadline }}</p>

                    <div class="ui large header">參與群組</div>
                    <p>
                        <div class="ui list">
                            <div class="item" v-for="(item, index) in info.voter" :key="index">{{ item }}</div>
                        </div>
                    </p>

                    <h4 class="ui horizontal divider header margin-20">所有組別</h4>
                    <p>
                        <div class="ui list">
                            <div class="item" @click="$router.push('/pineapple/groups/' + item._id)" v-for="(item, index) in groups_info" :key="index">{{ item.groups }}</div>
                        </div>
                    </p>
                    
                </div>

                <div class="ui segment" v-if="edit_open">

                    <div class="ui grid">
                        <div class="sixteen wide column right aligned">
                            <button class="circular ui icon button green" @click="edit"><i class="icon edit"></i></button>
                        </div>
                    </div>

                    <div class="ui segment basic">
                        <img :src="info.img" class="ui image rounded medium centered" />
                    </div>

                    <h4 class="ui horizontal divider header margin-20">名稱</h4>

                    <div class="ui form field">
                        <input type="text" v-model="info.title" />
                    </div>
                        
                    <h4 class="ui horizontal divider header margin-20">描述</h4>
                    
                    <div class="ui form field">
                        <textarea v-model="info.description"></textarea>
                    </div> 

                    <h4 class="ui horizontal divider header margin-20">活動時間</h4>

                    <div class="ui grid margin-20">
                        <div class="eight wide column">
                            <label>開始</label>
                            <date-picker :date="started" :option="option" :limit="limit"></date-picker>
                        </div>
                        <div class="eight wide column">
                            <label>結束</label>
                            <date-picker :date="deadline" :option="option" :limit="limit"></date-picker>    
                        </div>
                    </div>

                    <h4 class="ui horizontal divider header margin-20">參與群組</h4>
                    <p>
                        <div class="ui list">
                            <div class="item" v-for="(item, index) in info.voter" :key="index">{{ item }}</div>
                        </div>
                    </p>

                    <button class="ui button green fluid margin-20" @click="update_activity">更新</button>

                </div>
            </div>
            <!-- LEFT_END -->

            <!-- RIGHT -->
            <div class="eight wide column">
                <h2 class="ui header">
                    <i class="plus icon"></i>
                    <div class="content">新增組別</div>
                </h2>
                <div class="ui segment">
                    <sui-form>
                        <input name="_token" hidden value="{!! csrf_token() !!}" />
                        <h4 class="ui horizontal divider header margin-20">組別資料</h4>
                        <sui-form-field>
                            <label>名稱</label>
                            <input type="text" v-model="groups" />
                        </sui-form-field>

                        <sui-form-field>
                            <label>描述</label>
                            <textarea v-model="description"></textarea>
                        </sui-form-field>

                        <h4 class="ui horizontal divider header margin-20">封面</h4>

                        <div class="ui grid">
                            <div class="sixteen wide column" v-if="cover==''">
                                <input type="file" @change="onCoverChange" />
                            </div>
                            <div class="sixteen wide column right aligned" v-else>
                                <img v-img:name :src="cover" class="ui image small centered team-images" />
                                <button class="circular ui icon button red" @click="removeImage">
                                    <i class="trash alternate icon tiny"></i>
                                </button>
                            </div>
                        </div>

                        <h4 class="ui horizontal divider header margin-20">海報 (可多張)</h4>

                        <div class="ui grid">
                            <div class="sixteen wide column">
                                <input type="file" @change="onFileChange" id="select-img" multiple/>
                            </div>
                            
                            <div class="ui small bordered images" v-if="image_tmp!=''">
                                <div v-for="(item, index) in image_tmp" :key="index">
                                    <div class="sixteen wide column right aligned">
                                        <img v-img:name :src="item" class="ui image team-images" />
                                        <button class="circular ui icon button red" @click="removePhoto(index)">
                                            <i class="trash alternate icon tiny"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="ui small bordered images" v-if="uploaded_img!=''">
                                <div v-for="(item, index) in uploaded_img" :key="index">
                                    <div class="sixteen wide column right aligned">
                                        <img v-img:name :src="item" class="ui image team-images" />
                                    </div>
                                </div>
                            </div>

                            <button class="ui icon button fluid black" @click="uploadImage" v-if="image_tmp!=''">上傳</button>
                        </div>

                    </sui-form>

                    <div class="ui grid" v-if="image!=''">
                        <div class="sixteen wide column">
                            <button type="button" @click="finished" class="ui button basic fluid">完成</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- RIGHT_END -->
        </div>

    </div>
</template>

<script>

    import myDatepicker from 'vue-datepicker';

    export default {
        data: function () {
            return {
                info: [],
                groups_info: [],
                groups: '',
                description: '',
                image_tmp: [],
                permission: [],
                image: [],
                image_url: '',
                uploaded_img: [],
                cover: '',
                edit_open: false,
                username: '',
                token: '',
                started: {
                    time: ''
                },
                deadline: {
                    time: ''
                },
                option: {
                    type: 'day',
                    week: ['一', '二', '三', '四', '五', '六', '日'],
                    month: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                    format: 'YYYY-MM-DD',
                    placeholder: '選擇日期',
                    inputStyle: {
                    'display': 'inline-block',
                    'padding': '6px',
                    'line-height': '22px',
                    'font-size': '16px',
                    'box-shadow': '0 1px 3px 0 rgba(0, 0, 0, 0.2)',
                    'border-radius': '2px',
                    'color': '#5F5F5F'
                    },
                    color: {
                        header: '#066FA5',
                        headerText: '#E9EAED'
                    },
                    buttons: {
                        ok: '好的',
                        cancel: '取消'
                    },
                    overlayOpacity: 0.3,
                },
                limit: [{
                    type: 'weekday',
                    available: [1, 2, 3, 4, 5, 6, 7]
                }]
            }
        },
        components: {
            'date-picker': myDatepicker
        },
        methods: {
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

                this.image = [];
            },
            createImage(file) {
                
                var image = new Image();
                var reader = new FileReader();

                reader.onload = (e) => {
                    this.image_tmp.push(e.target.result);
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
                    url: '//127.0.0.1:8000/image/upload',
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    data: {
                        userImage: this.image_tmp,
                    }
                })
                .then(function (res) {
                    
                    var data = res.data;
                    for (var i =0; i< data.length; i++) {
                        
                        self.image.push(data[i].data.link);
                    }
                    
                    self.$swal({
                        title: "上傳成功！",
                        text: "輸入相關資料後，點擊送出完成新增。",
                        confirmButtonText: "知道了",
                    });
                    self.uploaded_img = self.image_tmp;
                    self.image_tmp = '';
                });
            },
            removeImage: function (e) {
                
                this.cover = '';
            },
            removePhoto: function(i) {
                this.image_tmp.splice(i, 1); 
            },
            onCoverChange: function (e) {

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.coverImage(files[0]);
            },
            coverImage: function (file) {

                var image  = new Image();
                var reader = new FileReader();
                var self   = this;

                reader.onload = (e) => {

                    this.cover = e.target.result;

                    axios({
                        method: 'post',
                        url: '//127.0.0.1:8000/image/upload',
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        data: {
                            userImage: this.cover,
                        }
                    })
                    .then(function (res) {

                        self.cover = res.data[0].data.link;
                    });
                };
            
                reader.readAsDataURL(file);
                
            },
            finished: function () {

                var self    = this;
                var router  = this.$router;
                axios({
                    method: 'post',
                    url: '//127.0.0.1:8000/pineapple/groups/create',
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    data: {
                        activity: this.$route.params.id,
                        groups: this.groups,
                        description: this.description,
                        cover: this.cover,
                        photo: this.image
                    }
                })
                .then(function (res) {
                    console.log(res);
                    self.$router.push('/pineapple/groups/' + res.data._id);
                    self.activity = '';
                    self.groups = '';
                    self.description = '';
                    self.cover = '';
                    self.photo = '';
                });
            },
            edit: function () {
                if (this.edit_open)
                {
                    this.edit_open = false;
                    return false;
                }
                this.edit_open = true;
            },
            logout: function () {
                axios.get('//127.0.0.1:8000/pineapple/logout')
                this.$router.go('/');
            },
            update_activity: function () {

                axios.post('//127.0.0.1:8000/pineapple/activity/update/set', {
                    title: this.info.title,
                    description: this.info.description,
                    started: this.started.time,
                    deadline: this.deadline.time,
                    id: this.$route.params.id
                })
                .then(function (res) {          

                });
            }
        },
        mounted: function () {

            var self = this;
            var router  = this.$router;
            
            axios.get('//127.0.0.1:8000/pineapple/activity/create/' + this.$route.params.id + '/get').then(response => {this.info = response.data});
            
            axios.get('//127.0.0.1:8000/pineapple/activity/groups/' + this.$route.params.id + '/get').then(response => {this.groups_info = response.data})

            axios.get('//127.0.0.1:8000/pineapple/login/status')
            .then(function (res) {
                
                self.token = res.data.token;
                self.username = res.data.username;
            });
        }
    }
</script>

