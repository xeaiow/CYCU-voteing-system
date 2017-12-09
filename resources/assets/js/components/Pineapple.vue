<template>
    <div>
        <div class="ui top fixed menu inverted">
        <a class="item">中原資管投票管理後臺</a>
        <a class="item">活動</a>
        <a class="item">系統</a>
        </div>
        

        <div class="ui grid cycuvote-container">
            <div class="three wide column">
                <!-- MENU -->
                <div class="ui inverted vertical menu">
                    <a class="active item"><i class="icon plus"></i>新增</a>
                    <a class="item"><i class="icon tasks"></i>列表</a>
                </div>
                <!-- MENU_END -->
            </div>
            <div class="thirteen wide column">
                <div class="ui segment">
                    <sui-form>
                        <sui-form-field>
                            <label>活動名稱</label>
                            <input type="text" v-model="title" />
                        </sui-form-field>
                        <sui-form-field>
                            <label>描述</label>
                            <textarea v-model="description"></textarea>
                        </sui-form-field>
                        <sui-form-field>
                            <label>適用對象</label>
                            <sui-checkbox v-model="permission[0]" label="大一" />
                            <sui-checkbox v-model="permission[1]" label="大二" />
                            <sui-checkbox v-model="permission[2]" label="大三" />
                            <sui-checkbox v-model="permission[3]" label="大四" />
                        </sui-form-field>
                        <div class="fields">
                            <div class="six wide field">
                                <label>開始時間</label>
                                <div class="ui input left icon">
                                    <i class="calendar icon"></i>
                                    <input type="date" v-model="started" />
                                </div>
                            </div>
                            <div class="six wide field">
                                <label>結束時間</label>
                                <div class="ui input left icon">
                                    <i class="calendar icon"></i>
                                    <input type="date" v-model="deadline" />
                                </div>
                            </div>
                        </div>
                    </sui-form>
                    <button type="button" class="ui button success" @click="create_activity">確定</button>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    export default {
        data: function () {
            return {
                permission: [],
                voter: [],
                title: '',
                description: '',
                started: '',
                deadline: ''
            }
        },
        methods: {
            create_activity: function () {

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
                                break;
                            case 1:
                                this.voter.push(dept[2]);
                                this.voter.push(dept[3]);
                                break;
                            case 2:
                                this.voter.push(dept[4]);
                                this.voter.push(dept[5]);
                                break;
                            case 3:
                                this.voter.push(dept[6]);
                                this.voter.push(dept[7]);
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
                        started: this.started,
                        deadline: this.deadline
                    }
                })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted: function () {
            
        }
    }
</script>

