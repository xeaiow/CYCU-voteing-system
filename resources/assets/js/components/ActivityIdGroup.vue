<template>
    <div>
        <div class="ui inverted menu cycuvote-theme cycuvote-menu fixed">
            <a class="active item font-style" @click="$router.push('/')">中原大學資訊管理學系投票系統</a>
            <a class="item font-style" @click="$router.push('/')">活動列表</a>
            <div class="right menu">
                <a class="item font-style" @click="$router.push('/')">登入</a>
            </div>
        </div>

        <div class="ui grid stackable cycuvote-container">
            <!-- card -->
            <div class="four wide column">
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
                <div class="ui bottom black attached button font-style" id="voting">投給他</div>
            </div>
            <div class="twelve wide column">
                <div class="ui piled segment project-content-text">
                    {{ items.discription }}
                </div>
                <h4 class="ui horizontal divider header">
                    <i class="image icon"></i> 海報預覽
                </h4>
                <div class="ui fluid images">
                    <img v-for="(activity, index) in activitys.pic" :key="index" v-bind:src="activity" class="team-images" />
                </div>
            </div>
        </div>

        <!-- 投票視窗 -->
        <div class="ui basic modal" id="voting-confirm">
            <div class="ui icon header">
                <i class="archive icon"></i> 確定投給這組嗎？
            </div>
            <div class="content">
                <p>
                    並將您寶貴的一票投給這組，同意送出後不得再次投票。
                </p>
            </div>
            <div class="actions">
                <div class="ui red basic cancel inverted button">
                    <i class="remove icon"></i> No
                </div>
                <div class="ui green ok inverted button">
                    <i class="checkmark icon"></i> Yes
                </div>
            </div>
        </div>

        <!-- 圖片預覽 -->
        <div class="ui basic modal view-images">
            <i class="close icon"></i>
            <div class="image content">
                <div class="ui Massive image centered">
                    <img src="http://140.135.67.5/Project/102GP/B12/images/4.png">
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
                activitys: {}
            }
        },
        methods: {
            greet: function(event) {
                window.location.href = '1/info';
            },

        },
        mounted: function() {
            axios.get('//127.0.0.1:8000/group/info/' + this.$route.params.id).then(response => {this.items = response.data})
            axios.get('//127.0.0.1:8000/activity/info/' + this.$route.params.id).then(response => {this.activitys = response.data})
        }
    }
</script>



