@extends('dashboard.master')




@section('content')


<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <!-- Page pre-title -->
              <div class="page-pretitle">
                Overview
              </div>
              <h2 class="page-title">
                Vertical transparent layout
              </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
              <div class="btn-list">
                {{-- <span class="d-none d-sm-inline">
                  <a href="#" class="btn">
                    New view
                  </a>
                </span> --}}
                <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#leave-type-report">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Create new employee
                </a>

                <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#leave-type-report" aria-label="Create new report">
                  <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- Page body -->
    <div class="page-body">

      <div class="container-xl">

        <div class="row row-deck row-cards">


          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Employees</h3>
              </div>
              <div class="row mb-3" style="padding: 20px">
                <div class="col-md-12">
                    <form action="{{ route('employees.index') }}" method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" name="query" value="{{ $query ?? '' }}" placeholder="Search by name or email">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>

              <div class="table-responsive">


                <table class="table card-table table-vcenter">
                   @forelse ($users as $item)
                   <tr>
                    <td class="w-100">
                      <a href="#" class="text-reset">{{ $item->name }}</a>
                    </td>
                    <td class="text-nowrap text-secondary">
                      <!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                      {{ $item->created_at }}
                    </td>
                    <td class="text-nowrap text-secondary">
                        <button class="btn btn-danger btn-delete">
                            Delete
                        </button>
                        <form class="d-inline" action="{{ route('employees.destroy', $item->id) }}" method="post">
                            @csrf
                            @method('delete')
                        </form>

                        <a href="#" class="btn btn-secondary d-none d-sm-inline-block edit-user-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#edit-user-modal"
                        data-id="{{ $item->id }}"
                        data-name="{{ $item->name }}"
                        data-email="{{ $item->email }}">
                        Edit Employee
                    </a>



                        <a href="{{ route('employee.request',$item->id) }}" class="btn">
                            Show Requests
                          </a>
                    </td>
                  </tr>
                   @empty
                   <h3 class="text-center">No Users Found</h3>

                   @endforelse
                </table>
                <div style="padding: 20px">
                {{ $users->links() }}

                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>

    @include('dashboard.layouts.footer')



  </div>

</div>



@include('dashboard.admin.employee._form')
@include('dashboard.admin.employee.edit_form')









  @endsection



  @section('scripts')

  <script>
    $(document).ready(function() {
        $('.edit-user-btn').click(function() {
            var userId = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');

            $('#edit-user-form').attr('action', '{{ url('employees') }}/' + userId);
            $('#edit-name').val(name);
            $('#edit-email').val(email);
        });
    });
</script>



  @endsection
