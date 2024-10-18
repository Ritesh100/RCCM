@extends('user.sidebar')
@section('content')
    <section>

        <div class="container">

            @if ($errors->any())
               
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                
            @endif

            <form action="/user/documentPost" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="Name">Name:</label>
                <input type="text" name="name" placeholder="Enter the name of the document"><br>

                <label for="Email">Email:</label>
                <input type="text" name="email" value="{{ $user->email }}" readonly><br>

                <label for="Document">Document:</label>
                <input type="file" name="doc_file" placeholder="pdf,word">

                <input type="submit" name="submit" value="Submit">

            </form>
        </div>
    </section>

    <table border="1">
        <tr>
            <th>S.N.</th>
            <th>Name</th>
            {{-- <th>Email</th> --}}
            <th>File</th>
        </tr>
        @foreach ($document as $key=>$doc)
        <tr>
           
            <td>{{++$key}}</td>
            <td>{{$doc->name}}</td>
            {{-- <td>{{$doc->email}}</td> --}}
            <td>
                <a href="{{ Storage::url($doc->path) }}" target="_blank">
                    View
                </a>
            </td>
        </tr>
        @endforeach
    </table>
@endsection
