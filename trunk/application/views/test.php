<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>


<script>
    $(document).ready(function () {
        var jsonArg1 = new Object();
        jsonArg1.Name = 'Sanjeev';
        jsonArg1.Email = '123@123.com';
        jsonArg1.Mobile1 = 57575577777;
        jsonArg1.Mobile2 = 5353535555;
        jsonArg1.Mobile3 = 4243355353553;
        jsonArg1.Mobile4 = 464646466464;
        jsonArg1.Mobile5=1343131311321;
        jsonArg1.DOB='1991-02-01';
        jsonArg1.Department='Hindi';
        jsonArg1.MorningBusRoute='203';
        jsonArg1.EveningBusRoute='205';
        jsonArg1.Sex="M";
        var jsonArg2 = new Object();
        jsonArg2.Name = 'Keshav Astha';
        jsonArg2.Email = '123@123.com';
        jsonArg2.Mobile1 = 57575577777;
        jsonArg2.Mobile2 = 5353535555;
        jsonArg2.Mobile3 = 4243355353553;
        jsonArg2.Mobile4 = 464646466464;
        jsonArg2.Mobile5=1343131311321;
        jsonArg2.DOB='1991-02-01';
        jsonArg2.Department='Hindi';
        jsonArg2.MorningBusRoute='203';
        jsonArg2.EveningBusRoute='205';
        jsonArg2.Sex="M";

        var parameters = new Array();
        parameters.push(jsonArg1);
        parameters.push(jsonArg2);
        $.ajax({
            type:"POST",
            url:'<?php echo URL::to('teacher/create');?>',
            Content:'application/json; charset=utf-8',
            data:JSON.stringify(parameters),

            success:function (e) {
                                   // your code ..
                    alert(e);


            },
            error:function (e) {
                alert(e);
            },
            dataType:'json'
        });

    });

</script>



