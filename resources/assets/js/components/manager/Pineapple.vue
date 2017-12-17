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
            <div class="three wide column">
                <!-- MENU -->
                <div class="ui c vertical menu">
                    <a class="active item" @click="$router.push('/pineapple')"><i class="icon plus"></i>新增</a>
                    <a class="item" @click="$router.push('/pineapple/activity')"><i class="icon tasks"></i>列表</a>
                </div>
                
                <!-- MENU_END -->
            </div>
            <div class="thirteen wide column">
                <div class="ui segment">

                    <h4 class="ui horizontal divider header">1. 活動海報</h4>

                    <div class="ui grid">
                        <div class="sixteen wide column right aligned" v-if="image">
                            <button class="ui icon button red tiny" @click="removeImage"><i class="minus icon"></i></button>
                        </div>
                    </div>

                    <div class="ui grid">
                        <div class="sixteen wide column" v-if="!image">
                            <input type="file" @change="onFileChange">
                        </div>
                        <div class="sixteen wide column center aligned" v-else>
                            <img :src="image" class="ui image medium centered" />
                        </div>
                    </div>

                    <sui-form>
                        <input name="_token" hidden value="{!! csrf_token() !!}" />
                        <h4 class="ui horizontal divider header margin-20">2. 活動資訊</h4>
                        <sui-form-field>
                            <label>標題</label>
                            <input type="text" v-model="title" />
                        </sui-form-field>

                        <sui-form-field>
                            <label>描述</label>
                            <textarea v-model="description"></textarea>
                        </sui-form-field>

                        <h4 class="ui horizontal divider header margin-20">3. 適用對象</h4>

                        <sui-form-field>
                            <sui-checkbox v-model="permission[0]" label="大一" />
                            <sui-checkbox v-model="permission[1]" label="大二" />
                            <sui-checkbox v-model="permission[2]" label="大三" />
                            <sui-checkbox v-model="permission[3]" label="大四" />
                        </sui-form-field>

                        <h4 class="ui horizontal divider header margin-20">4. 活動時間</h4>

                        <div class="fields">
                            <div class="five wide field">
                                <label>開始</label>
                                <date-picker :date="started" :option="option" :limit="limit"></date-picker>
                            </div>
                            <div class="five wide field">
                                <label>結束</label>
                                <date-picker :date="deadline" :option="option" :limit="limit"></date-picker>
                            </div>
                        </div>

                    </sui-form>

                    <div class="ui grid">
                        <div class="sixteen wide column margin-20">
                            <button type="button" class="ui button blue fluid" v-if="image_url != ''" @click="create_activity">新增</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    import myDatepicker from 'vue-datepicker';

    export default {
        data: function () {
            return {
                permission: [],
                voter_class: [],
                voter: [],
                title: '',
                description: '',
                image: '',
                image_url: '',
                token: '',
                username: '',
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
            create_activity: function () {

                var self = this;
                var dept = [ "資管一甲", "資管一乙", "資管二甲", "資管二乙", "資管三甲", "資管三乙", "資管四甲", "資管四乙"];
                
                for (var i = 0; i < 4; i++)
                {
                    if (this.permission[i] == true)
                    {
                        switch (i)
                        {
                            case 0:
                                this.voter.push(dept[0]);
                                this.voter.push(dept[1]);
                                this.voter_class.push(1);
                                break;
                            case 1:
                                this.voter.push(dept[2]);
                                this.voter.push(dept[3]);
                                this.voter_class.push(2);
                                break;
                            case 2:
                                this.voter.push(dept[4]);
                                this.voter.push(dept[5]);
                                this.voter_class.push(3);
                                break;
                            case 3:
                                this.voter.push(dept[6]);
                                this.voter.push(dept[7]);
                                this.voter_class.push(4);
                                break;
                        }
                    }
                }

                axios({
                    method: 'post',
                    url: '//127.0.0.1:8000/activity/create',
                    data: {
                        title: this.title,
                        description: this.description,
                        voter: this.voter,
                        voter_class: this.voter_class,
                        img: this.image_url,
                        started: this.started.time,
                        deadline: this.deadline.time
                    }
                })
                .then(function (response) {
                    
                    let link = response.data;

                    self.$swal({
                        title: "新增成功！",
                        text: "請接續新增組別。",
                        confirmButtonText: "知道了",
                    }).then(function (res) {

                        self.$router.push('/pineapple/groups/create/' + link);
                    });
                });
            },
            onFileChange: function (e)
            {
                
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {

                var image = new Image();
                var reader = new FileReader();

                reader.onload = (e) => {

                    this.image = e.target.result;
                    var self = this;
                    axios({
                        method: 'post',
                        url: '//127.0.0.1:8000/image/upload',
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        data: {
                            userImage: this.image,
                        }
                    })
                    .then(function (res) {
                        self.image_url = res.data[0].data.link;
                    });
                };
                reader.readAsDataURL(file);
            },
            removeImage: function (e) {
                
                this.image = '';
            },
            logout: function () {
                axios.get('//127.0.0.1:8000/pineapple/logout')
                this.$router.go('/');
            }
        },
        mounted: function () {

            var self = this;
            var router = this.$router;

            axios.get('//127.0.0.1:8000/pineapple/login/status')
            .then(function (res) {
                
                self.token = res.data.token;
                self.username = res.data.username;
            });
        }
    }
</script>

