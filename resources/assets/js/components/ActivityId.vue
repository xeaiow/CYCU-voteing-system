<template>
    <div>
        <div class="ui inverted menu cycuvote-theme cycuvote-menu fixed">
            <a class="active item font-style" @click="$router.push('/')">中原大學資訊管理學系投票系統</a>
            <a class="item font-style" @click="$router.push('/')">活動列表</a>
            <div class="right menu">
                <a class="item font-style" @click="$router.push('/')">登入</a>
            </div>
        </div>

        <div class="ui grid cycuvote-container">

            <div class="sixteen wide column">
                <h2 class="ui icon header center aligned">
                    <i class="lab icon"></i>
                    <div class="content font-style">
                        106 中原資管專題複賽
                    </div>
                    <div class="sub header">每人一票，且不得重複 ｜ 大一 ～ 大三 ｜ 2017/10/26 ~ 2017/12/31</div>
                </h2>
            </div>

            <div class="sixteen wide column">

                <div class="ui stackable three column grid example-2">
                    <div class="column" v-for="(item, index) in items" :key="index">
                        <div class="ui cards stackable">
                            <div class="card pointer" @click="$router.push({path:'/group/' + item._id})">
                                <div class="content">
                                    {{ item.groups }}
                                </div>
                                <div class="image">
                                    <img v-bind:src="item.img" />
                                </div>
                            </div>
                        </div>
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
                items: []
            }
        },
        methods: {
            greet: function(event) {
                window.location.href = '1/info';
            },

        },
        mounted: function() {
            axios.get('//127.0.0.1:8000/groups/info/' + this.$route.params.id).then(response => {this.items = response.data})
        }
    }
</script>



