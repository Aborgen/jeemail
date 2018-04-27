(() => {
    const winObj   = window;
    const html     = document.getElementsByTagName('html')[0];
    const property = 'innerHeight';
    const value    = document.documentElement.clientHeight;
    const get      = () => html.clientHeight;
    if (!(property in winObj)) {
        Object.defineProperty(winObj, property, { get });
    }
})();
