@extends('user.sidebar')
@section('content')
<section>

    <div class="container">
        <form action="">
            <label for="Name">Name:</label>
            <input type="text" name="name" placeholder="Enter the name of the document"><br>

            <label for="Email">Email:</label>
            <input type="text" name="email" value="{{$user_email}}" readonly><br>

            <label for="Document">Document:</label>
            <input type="file" name="doc_file" placeholder="pdf,word">

        </form>
    </div>
</section>
@endsection