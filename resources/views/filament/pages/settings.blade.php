<x-filament-panels::page>
    <p class="text-sm text-gray-500">كلشي كيتبدل من هنا وكيبان فالموقع مباشرة: الأرقام، العناوين، وروابط الصفحات.</p>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}
        <x-filament::button type="submit">حفظ الإعدادات</x-filament::button>
    </form>
</x-filament-panels::page>
