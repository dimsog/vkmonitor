export default class {
    static extract(groupLink) {
        return groupLink
            .split('?')[0]
            .replace(/https\:\/\/vk\.com\/|https\:\/\/m\.vk\.com\/|http\:\/\/vk\.com\/|http\:\/\/m\.vk\.com\//, '')
            .trim();
    }
}
