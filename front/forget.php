<fieldset>
    <legend>忘記密碼</legend>
    <div>請輸入信箱以查詢密碼</div>
    <div>
        <input type="text" name="email" id="email">
    </div>
    <div id="result"></div>
    <div>
        <input type="button" value="尋找" onclick="forget()">
    </div>
</fieldset>
<script>
    function forget(){
        let email=$("#email").val()
        $.get("./api/forget.php",{email},(res)=>{
            $("#result").text(res)
        })
    }
</script>