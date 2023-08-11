<div class="modal modal-blur fade" id="edit-leave-type-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <form action="{{ route('leave-types.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title">New Leave Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" class="form-control @error('description') is-invalid @enderror" name="title" placeholder="Your report name" value="{{ $item->title }}">

              @error('title')
              <small class="invalid-feedback">{{ $message }}</small>
              @enderror
            </div>
            <div class="col-lg-12">
              <div>
                <label class="form-label">Additional information</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">
                    {{ $item->description }} </textarea>
                @error('description')
                <small class="invalid-feedback">{{ $message }}</small>
                @enderror
              </div>
            </div>

          </div>

          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              Cancel
            </a>

            <button type="submit" class="btn btn-primary ms-auto" >
                Update the leave type
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

