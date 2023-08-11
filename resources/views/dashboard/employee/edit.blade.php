@extends('dashboard.master')



@section('content')


<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h2 class="page-title">
              Invoice
            </h2>
          </div>
          <!-- Page title actions -->
          <div class="col-auto ms-auto d-print-none">
            <button type="button" class="btn btn-primary" onclick="javascript:window.print();">
              <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
              Print Invoice
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">

        <form action="{{ route('my-requests.update', $leaveRequest->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">From </label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ $leaveRequest->start_date }}">
                            @error('start_date')
                            <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">To</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ $leaveRequest->end_date }}">
                            @error('end_date')
                            <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div>
                            <label class="form-label">The Reason</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror"  name="reason" rows="3">{{ $leaveRequest->reason }}</textarea>
                            @error('reason')
                            <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">Leave Type</label>
                            <select class="form-select @error('leave_type_id') is-invalid @enderror" name="leave_type_id" aria-label="Select a Leave Type">
                                <option selected disabled>Select a Leave Type</option>

                                @foreach ($leavetype as $i)
                                    <option value="{{ $i->id }}" {{ $leaveRequest->leave_type_id == $i->id ? 'selected' : '' }}>
                                        {{ $i->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                            <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>



                     <!-- Page title actions -->
          {{-- <div class="col-auto ms-auto d-print-none"> --}}
            <button type="button" class="btn btn-primary">

              Update Request
            </button>
          {{-- </div> --}}

                </div>
            </div>
        </form>


    </div>

  </div>

@endsection
