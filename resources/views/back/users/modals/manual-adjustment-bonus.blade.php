<div class="modal fade" id="manual-adjustments-bonus-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Manual adjustment bonus') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('bonus-system.campaigns.users.manual-adjustments')}}" method="post" id="manual-adjustments-bonus-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="wallet">{{ _i('Bonus wallets') }} ({{ session('currency') }})</label>
                        <select name="wallet" id="wallet" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            @if(isset($wallets_bonuses))
                                @foreach ($wallets_bonuses as $bonusWallet)
                                    <option value="{{ $bonusWallet->id }}">
                                        {{ $bonusWallet->provider_type }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">{{ _i('Amount') }}</label>
                        <input type="number" name="amount" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label for="transaction_type">{{ _i('Transaction type') }}</label>
                        <select name="transaction_type" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            <option value="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$credit }}">
                                {{ _i('Credit') }}
                            </option>
                            <option value="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$debit }}">
                                {{ _i('Debit') }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">{{ _i('Description') }}</label>
                        <textarea name="description" cols="30" rows="5"
                                  class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" class="user" value="{{ isset($user) ? $user->id : '' }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="manual-adjustment-bonus" data-loading-text="{{ _i('Please wait...') }}">
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
