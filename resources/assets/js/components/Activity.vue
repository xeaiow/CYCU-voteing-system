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
                <div class="ui stackable three column grid">

                    <div class="column" v-for="(item, index) in items" :key="index">
                        <div class="ui cards stackable">
                            <div class="card pointer" @click="$router.push({path:'activity/'+ item._id})">
                                <div class="content">
                                    {{ item.title }}
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
                items:[]
            }
        },
        mounted: function () {
            axios.get('//127.0.0.1:8000/activity/get').then(response => {this.items = response.data;})
        }
    }
</script>

