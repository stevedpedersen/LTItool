{if $launch->is_deep_link_launch()}
    <div class="dl-config" style="padding:24px;">
        <h1>Pick a Difficulty</h1>
        <ul>
            <li><a href="{$serviceName}/configure?diff=easy&launch_id={$launch->get_launch_id()}">Easy</a></li>
            <li><a href="{$serviceName}/configure?diff=normal&launch_id={$launch->get_launch_id()}">Normal</a></li>
            <li><a href="{$serviceName}/configure?diff=hard&launch_id={$launch->get_launch_id()}">Hard</a></li>
        </ul>
    </div>

{else}
<div id="game-screen">
    <div style="position:absolute;width:1000px;margin-left:-500px;left:50%; display:block">
        <div id="scoreboard" style="position:absolute; right:0; width:200px; height:500px">
            <h2 style="margin-left:12px;color:#fff;">Scoreboard</h2>
            <table id="leadertable" style="margin-left:12px;">
                <caption id="leadercaption" style="color:#fff;"></caption>
            </table>
            <table id="grouptable" style="margin-left:12px;">
                <caption id="groupcaption" style="color:#fff;"></caption>
            </table>
        </div>
        <canvas id="breakoutbg" width="800" height="500" style="position:absolute;left:0;border:0;">
        </canvas>
        <canvas id="breakout" width="800" height="500" style="position:absolute;left:0;">
        </canvas>
    </div>
</div>
{/if}

<script>
    var curr_diff = "{$curr_diff}";
    var launch_id = "{$launch->get_launch_id()}";
    var curr_user_name = "{$curr_user_name}";
</script>