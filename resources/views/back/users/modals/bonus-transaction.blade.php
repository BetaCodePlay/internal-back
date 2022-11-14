<div class="modal fade" id="bonus-transaction-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Assign bonus') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.bonus-transactions') }}" method="post" id="bonus-transactions-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="amount">{{ _i('Amount') }}</label>
                        <input type="number" name="amount" id="amount" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label for="allocation_criteria">{{ _i('Allocation criteria') }}</label>
                        <select name="allocation_criteria" id="allocation_criteria" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            @if(isset($campaigns))
                                @foreach ($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}">
                                        {{ $campaign->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">{{ _i('Description') }}</label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" value="{{ $user->id }}">
                    <input type="hidden" name="wallet" value="{{ $wallet->id }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="bonus-transactions"
                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Accept') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
