@extends('admin.layout')

@section('content')
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Предзаказы курсов</h1>
            <p class="text-sm text-slate-500">Просматривайте заявки на скидку перед запуском программ.</p>
        </div>
        <div class="grid w-full max-w-md grid-cols-1 gap-3 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm">
                <div class="text-slate-500">Всего заявок</div>
                <div class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($totalPreorders, 0, ',', ' ') }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm">
                <div class="text-slate-500">Курсов с предзаказами</div>
                <div class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($uniqueCoursesCount, 0, ',', ' ') }}</div>
            </div>
        </div>
    </div>

    @if ($preorders->isEmpty())
        <div class="mt-8 rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center text-slate-500">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h.008v.008H15V9Zm-3 0h.008v.008H12V9Zm-3 0h.008v.008H9V9Zm0 3h.008v.008H9V12Zm3 0h.008v.008H12V12Zm3 0h.008v.008H15V12Zm-6 3h.008v.008H9V15Zm3 0h.008v.008H12V15Zm3 0h.008v.008H15V15Zm-9 3h12M4.5 6h15m-1.5-3h-12A1.5 1.5 0 0 0 4.5 4.5v15A1.5 1.5 0 0 0 6 21h12a1.5 1.5 0 0 0 1.5-1.5v-15A1.5 1.5 0 0 0 18 3Z" />
                </svg>
            </div>
            <h2 class="mt-4 text-xl font-semibold text-slate-800">Пока нет заявок</h2>
            <p class="mt-2 text-sm">Как только студенты начнут оставлять заявки на предзаказ, они появятся в этом разделе.</p>
        </div>
    @else
        <div class="mt-8 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3">Курс</th>
                    <th class="px-6 py-3">Контакт</th>
                    <th class="px-6 py-3">Старт курса</th>
                    <th class="px-6 py-3">Создано</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @foreach ($preorders as $preorder)
                    @php
                        $course = $preorder->course;
                        $user = $preorder->user;
                        $courseTitle = $course?->title ?? 'Курс удалён';
                        $courseStartDate = $course?->start_date;
                        $courseStartFormatted = $courseStartDate?->format('d.m.Y H:i');
                        $courseStartStatus = null;

                        if ($course && $courseStartDate) {
                            $courseStartStatus = $course->isUpcoming()
                                ? 'Старт через ' . $courseStartDate->diffForHumans(null, true)
                                : 'Уже доступен';
                        }

                        $contactName = $user?->name ?? $preorder->name ?? 'Без имени';
                        $contactDetails = $user?->email;
                        $createdAt = $preorder->created_at?->format('d.m.Y H:i');
                    @endphp
                    <tr class="align-top">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $courseTitle }}</div>
                            @if ($course)
                                <div class="mt-1 text-xs text-slate-500">ID: {{ $course->id }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <div class="font-semibold text-slate-700">{{ $contactName }}</div>
                            <div class="text-xs text-slate-500">{{ $preorder->contact }}</div>
                            @if ($contactDetails)
                                <div class="text-xs text-slate-400">{{ $contactDetails }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($courseStartFormatted)
                                <div class="text-sm font-medium text-slate-700">{{ $courseStartFormatted }}</div>
                                @if ($courseStartStatus)
                                    <div class="text-xs {{ $course && $course->isUpcoming() ? 'text-blue-600' : 'text-emerald-600' }}">{{ $courseStartStatus }}</div>
                                @endif
                            @else
                                <span class="text-xs text-slate-500">Дата не указана</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <div class="text-sm font-medium text-slate-700">{{ $createdAt }}</div>
                            <div class="text-xs text-slate-500">#{{ $preorder->id }}</div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $preorders->links() }}
        </div>
    @endif
@endsection
