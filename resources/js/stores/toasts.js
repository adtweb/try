import Alpine from "alpinejs";

Alpine.store("toasts", {
    toasts: [],
    indexAI: 1,
    titles: {
        info: {
            ru: "Информация",
            en: "Information",
        },
        error: {
            ru: "Ошибка!",
            en: "Error!",
        },
        success: {
            ru: "Успешно",
            en: "Success",
        },
    },

    styles(toast) {
        return {
            "tw-bg-blue tw-text-white": toast.type === "info",
            "tw-bg-danger tw-text-white": toast.type === "error",
            "tw-bg-success tw-text-white": toast.type === "success",
        };
    },
    push(text, type, delay = 0, title = null, link = null) {
        const message = {
            id: this.indexAI++,
            message: text,
            type: type,
            title: title,
            link: link,
        };

        this.toasts.push(message);

        if (delay > 0) {
            setTimeout(() => {
                this.remove(this.indexAI - 1);
            }, delay);
        }
    },
    remove(id) {
        let toast = this.get(id);
        let index = this.toasts.indexOf(toast);
        this.toasts.splice(index, 1);
    },
    pushError(message, delay = 0, title = null, link = null) {
        if (title === null) {
            title = this.titles["error"][Alpine.store("locale")];
        }
        this.push(message, "error", delay, title, link);
    },
    pushSuccess(message, delay = 0, title = null, link = null) {
        if (title === null) {
            title = this.titles["success"][Alpine.store("locale")];
        }
        this.push(message, "success", delay, title, link);
    },
    pushInfo(message, delay = 0, title = null, link = null) {
        if (title === null) {
            title = this.titles["info"][Alpine.store("locale")];
        }
        this.push(message, "info", delay, title, link);
    },
    get(id) {
        return this.toasts.find((el) => el.id === id);
    },
    handleResponseError(error) {
		if (error.response?.status === 422) {
            return;
        }
        this.pushError(error.response?.data?.message ?? error.message);
    },
});
