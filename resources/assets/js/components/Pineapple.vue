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
                    <form class="ui form">
                        <div class="field">
                            <label>活動名稱</label>
                            <input type="text" />
                        </div>
                        <div class="field">
                            <label>活動名稱</label>
                            <input type="text" />
                        </div>
                        <div class="field">
                            <label>簡述說明</label>
                            <textarea></textarea>
                        </div>
                        <div class="inline field">
                            <div class="ui checkbox">
                                <input type="checkbox" tabindex="0" class="hidden">
                                <label>大一</label>
                            </div>
                        </div>

                        
                        <button class="ui button" type="submit">Submit</button>
                        </form>
                </div>
            </div>
        </div>
    </div>

</template>
