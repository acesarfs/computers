@extends('master')

@section('content')

<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Patrimônio</th>
      <th scope="col">IP</th>
      <th scope="col">Inserido no domínio em</th>
      <th scope="col">Sistema Operacional</th>
      <th scope="col">Setor</th>
      <th scope="col">Responsável</th>
    </tr>
  </thead>
  <tbody>

@foreach ($computers as $computer)
    <tr>
      @isset($computer['setor'])
      <td>{{ $computer['hostname'] }}</td>
      <td>{{ $computer['ip'] }}</td>
      <td>{{ $computer['created'] }}</td>
      <td>{{ $computer['os'] }}</td>
      <td>{{ $computer['setor'] }}</td>
      <td>{{ $computer['responsavel'] }}</td>
      @else
      <td><b style="color:red;">{{ $computer['hostname'] }}</b></td>
      <td>{{ $computer['created'] }}</td>
      <td>{{ $computer['os'] }}</td>
      <td><b style="color:red;">HOSTNAME ERRADO</b></td>
      <td></td>
      @endisset
    </tr>
@endforeach


  </tbody>
</table>

@endsection
