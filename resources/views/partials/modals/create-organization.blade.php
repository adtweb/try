<x-popup id="create-organization" size="lg" :title="__('my/events/create.add_organization_title')">
    <form class="form tw-flex tw-flex-col tw-gap-2" @submit.prevent="submit" x-data="{
        form: $form('post', '{{ route('organization.store') }}', {
            full_name_ru: '',
            short_name_ru: '',
            full_name_en: '',
            short_name_en: '',
            inn: '',
            address: '',
            phone: '',
            whatsapp: '',
            telegram: '',
            type: 'Университет',
            actions: [],
            logo: '',
            vk: '',
        }),
    
        actions: null,
        loading: false,
    
        init() {
            this.handleActions()
        },
        submit() {
            this.loading = true
    
            this.form.submit()
                .then(response => {
                    this.$dispatch('popup-close', 'create-organization')
    
                    this.$dispatch('select-add-option', {
                        name: 'organization_id',
                        option: {
                            value: response.data.organization.id,
                            text: response.data.organization.full_name_{{ loc() }}
                        }
                    })
    
                    this.form.reset()
                })
                .catch(error => {
                    this.$store.toasts.handleResponseError(error);
                })
                .finally(() => {
                    this.loading = false
                })
        },
        handleActions() {
            let result = []
            this.actions = document.querySelectorAll('[name=\'actions\']')
            this.actions.forEach(el => {
                if (el.checked) {
                    result.push(el.value)
                }
    
                if (el.getAttribute('type') === 'text') {
                    if (el.value.trim() === '') {
                        result.pop()
                    } else {
                        result.push(el.value)
                    }
                }
            })
            this.form.actions = result
            this.form.validate('actions')
        },
    }">
        <div class="form__row">
            <label class="form__label" for="o_4">@lang('auth.register.org_title_ru') (*)</label>
            <div class="form__line" :class="form.invalid('full_name_ru') && '_error'">
                <input id="o_4" class="form__input input" autocomplete="off" type="text" name="full_name_ru"
                    placeholder="@lang('auth.register.org_title_ru')" x-model="form.full_name_ru"
                    @input.debounce.1000ms="form.validate('full_name_ru')">
                <template x-if="form.invalid('full_name_ru')">
                    <div class="form__error" x-text="form.errors.full_name_ru"></div>
                </template>
            </div>
            <div class="form__line" :class="form.invalid('short_name_ru') && '_error'">
                <input class="form__input input" autocomplete="off" type="text" name="short_name_ru"
                    placeholder="@lang('auth.register.short_title_ru')" x-model="form.short_name_ru"
                    @input.debounce.1000ms="form.validate('short_name_ru')">
                <template x-if="form.invalid('short_name_ru')">
                    <div class="form__error" x-text="form.errors.short_name_ru"></div>
                </template>
            </div>
        </div>

        <div class="form__row">
            <label class="form__label" for="o_4">@lang('auth.register.org_title_en') (*)</label>
            <div class="form__line" :class="form.invalid('full_name_en') && '_error'">
                <input id="o_4" class="form__input input" autocomplete="off" type="text" name="full_name_en"
                    placeholder="@lang('auth.register.org_title_en')" x-model="form.full_name_en"
                    @input.debounce.1000ms="form.validate('full_name_en')">
                <template x-if="form.invalid('full_name_en')">
                    <div class="form__error" x-text="form.errors.full_name_en"></div>
                </template>
            </div>
            <div class="form__line" :class="form.invalid('short_name_en') && '_error'">
                <input class="form__input input" autocomplete="off" type="text" name="short_name_en"
                    placeholder="@lang('auth.register.short_title_en')" x-model="form.short_name_en"
                    @input.debounce.1000ms="form.validate('short_name_en')">
                <template x-if="form.invalid('short_name_en')">
                    <div class="form__error" x-text="form.errors.short_name_en"></div>
                </template>
            </div>
        </div>

        <div class="form__row" :class="form.invalid('inn') && '_error'">
            <label class="form__label" for="o_5">{{ __('auth.register.inn') }}</label>
            <div class="form__line">
                <input id="o_5" class="input" autocomplete="off" type="text" name="inn"
                    placeholder="{{ __('auth.register.inn') }}" x-model="form.inn"
                    @input.debounce.1000ms="form.validate('inn')">
                <template x-if="form.invalid('inn')">
                    <div class="form__error" x-text="form.errors.inn"></div>
                </template>
            </div>
        </div>

        <div class="form__row" :class="form.invalid('address') && '_error'">
            <label class="form__label" for="o_6">{{ __('auth.register.address') }} (*)</label>
            <textarea id="o_6" autocomplete="off" name="address" placeholder="{{ __('auth.register.address') }}"
                class="input _smaller" x-model="form.address" @input.debounce.1000ms="form.validate('address')"></textarea>
            <template x-if="form.invalid('address')">
                <div class="form__error" x-text="form.errors.address"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('phone') && '_error'">
            <label class="form__label" for="o_7">{{ __('auth.register.phone') }} (*)</label>
            <div class="form__line" x-data>
                <input id="o_7" class="input" autocomplete="off" type="text" name="phone"
                    placeholder="{{ __('auth.register.phone') }}" x-model="form.phone"
                    @input.debounce.1000ms="form.validate('phone')">
                <template x-if="form.invalid('phone')">
                    <div class="form__error" x-text="form.errors.phone"></div>
                </template>
            </div>
        </div>

        <div class="form__row" :class="form.invalid('whatsapp') && '_error'">
            <label class="form__label" for="o_8">{{ __('auth.register.whatsapp') }}</label>
            <div class="form__line">
                <input id="o_8" class="input" autocomplete="off" type="text" name="whatsapp"
                    placeholder="https://wa.me/70001234567" x-model="form.whatsapp"
                    @input.debounce.1000ms="form.validate('whatsapp')">
                <template x-if="form.invalid('whatsapp')">
                    <div class="form__error" x-text="form.errors.whatsapp"></div>
                </template>
            </div>
        </div>

        <div class="form__row" :class="form.invalid('telegram') && '_error'">
            <label class="form__label" for="o_8">{{ __('auth.register.telegram') }}</label>
            <div class="form__line">
                <input id="o_8" class="input" autocomplete="off" type="text" name="telegram"
                    placeholder="https://t.me/login" x-model="form.telegram"
                    @input.debounce.1000ms="form.validate('telegram')">
                <template x-if="form.invalid('telegram')">
                    <div class="form__error" x-text="form.errors.telegram"></div>
                </template>
            </div>
        </div>

        <div id="organization_type" class="form__row" :class="form.invalid('type') && '_error'"
            x-data="{
                other: false,
                select() {
                    let item = this.$event.detail.select.parentElement.querySelector('.select__content').innerText
                    this.form.type = item
                    if (item === 'Другое') {
                        this.other = true
                        this.form.type = ''
                    } else {
                        this.other = false
                    }
                },
            }">
            <label class="form__label" for="s_1">{{ __('auth.register.organization_type') }} (*)</label>
            <div class="form__line">
                <select id="s_1" name="type" data-scroll="500" data-class-modif="form"
                    @select-callback.camel.document="select">
                    <option value="Университет" selected>{{ __('auth.register.university') }}</option>
                    <option value="Институт">{{ __('auth.register.institute') }}</option>
                    <option value="Научно-исследовательский институт">{{ __('auth.register.research_institute') }}
                    </option>
                    <option value="Некоммерческая организация">{{ __('auth.register.non_commerce') }}</option>
                    <option value="Коммерческая организация">{{ __('auth.register.commerce') }}</option>
                    <option value="Другое">{{ __('auth.register.other') }}</option>
                </select>
            </div>
            <div class="form__line" x-show="other" x-transition>
                <template x-if="other">
                    <input class="input" autocomplete="off" type="text" name="type"
                        placeholder="Введите тип организации" x-model="form.type"
                        @input.debounce.1000ms="form.validate('type')">
                </template>
                <template x-if="form.invalid('type')">
                    <div class="form__error" x-text="form.errors.type"></div>
                </template>
            </div>
        </div>

        <div id="actions" class="form__row _one" x-data="{ other: false }">
            <label class="form__label" for="s_2">{{ __('auth.register.activity') }} (*)</label>

            <input id="oc_1" type="checkbox" class="checkbox__input" name="actions"
                value="{{ __('auth.register.science') }}" checked @change="handleActions">
            <label for="oc_1" class="checkbox__label">
                <span class="checkbox__text">{{ __('auth.register.science') }}</span>
            </label>

            <input id="oc_2" type="checkbox" class="checkbox__input" name="actions"
                value="{{ __('auth.register.education') }}" @change="handleActions">
            <label for="oc_2" class="checkbox__label">
                <span class="checkbox__text">{{ __('auth.register.education') }}</span>
            </label>

            <input id="oc_3" type="checkbox" class="checkbox__input" name="actions"
                value="{{ __('auth.register.commercial') }}" @change="handleActions">
            <label for="oc_3" class="checkbox__label">
                <span class="checkbox__text">{{ __('auth.register.commercial') }}</span>
            </label>

            <input id="oc_4" type="checkbox" class="checkbox__input" name="actions"
                value="{{ __('auth.register.other') }}" x-model="other" @change="handleActions">
            <label for="oc_4" class="checkbox__label">
                <span class="checkbox__text">{{ __('auth.register.other') }}</span>
            </label>

            <div class="form__line" :class="form.invalid('actions') && '_error'" x-show="other" x-transition x-cloak>
                <template x-if="other">
                    <input class="input" autocomplete="off" type="text" name="actions"
                        placeholder="{{ __('auth.register.enter_activity') }}" @change="handleActions">
                </template>
            </div>
            <template x-if="form.invalid('actions')">
                <div class="form__error" x-text="form.errors.actions"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('vk') && '_error'">
            <label class="form__label" for="o_9">{{ __('auth.register.vk') }}</label>
            <div class="form__line">
                <input id="o_9" class="input" autocomplete="off" type="text" name="vk"
                    placeholder="{{ __('auth.register.vk_placeholder') }}" x-model="form.vk"
                    @input.debounce.1000ms="form.validate('vk')">
                <template x-if="form.invalid('vk')">
                    <div class="form__error" x-text="form.errors.vk"></div>
                </template>
            </div>
        </div>

        <div class="form__row">
            <button class="form__button button button_primary" :disabled="form.processing" type="submit">
                {{ __('my/organization.create.btn') }}
                <x-loader class="tw-w-5 tw-h-4" />
            </button>
        </div>
    </form>
</x-popup>
