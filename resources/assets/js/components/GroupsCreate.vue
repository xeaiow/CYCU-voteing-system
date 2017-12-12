<template>
    <div>
        <div class="ui top fixed menu inverted">
            <a class="item">中原資管投票管理後臺</a>
            <a class="item">活動</a>
            <a class="item">系統</a>
        </div>

        <div class="ui grid cycuvote-container">

            <!-- LEFT -->
            <div class="eight wide column">
                <div class="ui segment">
                    <h2 class="ui icon header">活動名稱：{{ info.title }}</h2>

                    <img :src="info.img" class="ui image medium centered" />

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
                </div>
            </div>
            <!-- LEFT_END -->

            <!-- RIGHT -->
            <div class="eight wide column">
                <div class="ui segment">
                <sui-form>
                        <input name="_token" hidden value="{!! csrf_token() !!}" />
                        <h4 class="ui horizontal divider header margin-20">組別資料</h4>
                        <sui-form-field>
                            <label>組別名稱</label>
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
                            <div class="sixteen wide column center aligned" v-else>
                                <img :src="cover" class="ui image medium centered" />
                                <button class="ui icon button red" @click="onCoverChange"><i class="minus icon tiny"></i></button>
                            </div>
                        </div>

                        <h4 class="ui horizontal divider header margin-20">海報 (可多張)</h4>

                        <div class="ui grid">
                            <div class="sixteen wide column">
                                <input type="file" @change="onFileChange" id="select-img" multiple/>
                                <button class="ui icon button basic tiny" @click="uploadImage">上傳</button>
                            </div>
                            <div class="sixteen wide column center aligned" v-if="image">
                                <img :src="image" class="ui image medium centered" />
                            </div>
                        </div>

                    </sui-form>

                    <div class="ui grid">
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
    export default {
        data: function () {
            return {
                info: [],
                groups: '',
                description: '',
                image_tmp: [],
                image: [],
                image_url: '',
                cover: ''  
            }
        },
        methods: {
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

                    this.image_tmp.push(e.target.result);
                };
               
                reader.readAsDataURL(file);    
                document.getElementById("select-img").value = "";
                this.$swal({
                    title: "選取成功！",
                    text: "如需增加圖片請繼續選取，完成後請點擊上傳。",
                    confirmButtonText: "知道了",
                });
            },
            uploadImage: function () {

                var self = this;
                axios({
                    method: 'post',
                    url: '//140.135.112.191/image/upload',
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
                });
            },
            removeImage: function (e) {
                
                this.image = '';
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
                        url: '//140.135.112.191/image/upload',
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
                    url: '//140.135.112.191/pineapple/groups/create',
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

                    self.$swal({
                        title: "新增完成！",
                        text: "如有其他資料，請接續新增即可。",
                        confirmButtonText: "知道了",
                    });
                    self.activity = '';
                    self.groups = '';
                    self.description = '';
                    self.cover = '';
                    self.photo = '';
                });
            }
        },
        mounted: function () {

            var router  = this.$router;
            axios.get('//140.135.112.191/pineapple/activity/create/' + this.$route.params.id + '/get').then(response => {this.info = response.data;console.log(this.info)})
        }
    }
</script>

