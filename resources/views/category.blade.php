<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="_token" content="{{csrf_token()}}" />
    <title>category Store</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="container">
    <label class="h2 my-3"> Adding Category </label>
    <div class="alert alert-success" style="display:none"></div>
    <form id="myForm">
        <div class="form-group">
            <label for="parentID">Category Parent:</label>
            <select id="parentID" class="form-control">
                @if(isset($catParentList))
                    <option value="0">Static (no parent)</option>
                    @foreach($catParentList as $parent)
                        <option value="{{$parent->id}}">{{$parent->cat_name}}</option>
                    @endforeach
                    @else
                    <option value="0">Static (no parent)</option>
                @endif
            </select>
        </div>
        <div class="form-group">
            <label for="name">Category Name:</label>
            <input type="text"  id="name" name="name"
            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <button class="btn btn-primary" id="ajaxSubmit">Save</button>
    </form>


</div>
<script src="http://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
</script>
<script>
    jQuery(document).ready(function(){
        jQuery('#ajaxSubmit').click(function(e){
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/category/post') }}",
                method: 'post',
                data: {
                    name: jQuery('#name').val(),
                    parentID: jQuery('#parentID').val(),
                },
                success: function(result){
                    jQuery('.alert').show();
                    jQuery('.alert').html(result.success);
                    document.getElementById('name').value='';
                }});
        });
            jQuery('#parentID').on('change click', function() {
                var parentId=this.value;

                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    type:"GET",
                    url:"/categoryparent/"+parentId,
                    success : function(results) {
                         var listOfParent= results.success;
                         var s = $(document.getElementById('parentID'));
                         if(listOfParent.length > 0) {
                             $('#parentID').empty();
                             for (var val in listOfParent) {
                                 $("<option />", {value: listOfParent[val]['id'], text: listOfParent[val]['cat_name']}).appendTo(s);
                             }
                         }
                         s.appendTo("parentID");
                    }
                });
            });
    });
</script>
</body>
</html>