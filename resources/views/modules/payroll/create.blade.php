<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">New Payroll Run</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Configure the parameters for the next payroll cycle.</p>
            </div>
            <a href="{{ route('payroll.index') }}" class="btn btn-ghost btn-sm btn-outline">
                <span class="material-symbols-outlined text-base">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6 md:p-8">
                <form action="{{ route('payroll.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold">Payroll Period</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <select name="month" class="select select-bordered w-full" required>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <select name="year" class="select select-bordered w-full" required>
                                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label" for="pay_date">
                                <span class="label-text font-bold">Payment Date</span>
                            </label>
                            <input id="pay_date" name="pay_date" type="date" class="input input-bordered w-full" value="{{ date('Y-m-t') }}" required />
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label" for="notes">
                            <span class="label-text font-bold">Notes</span>
                        </label>
                        <textarea id="notes" name="notes" class="textarea textarea-bordered w-full" rows="3" placeholder="Optional notes..."></textarea>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full btn btn-primary">
                            Initialize Run
                        </button>
                        <div class="flex items-center justify-center gap-2 mt-6 opacity-40">
                            <span class="material-symbols-outlined text-sm">verified_user</span>
                            <p class="text-[9px] font-bold uppercase tracking-wider">Secure Audit Active</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
