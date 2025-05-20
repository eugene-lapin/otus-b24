BX.addCustomEvent(
    'BX.UI.EntityUserFieldLayoutLoader:onUserFieldDeployed',
    function() {
        const inputX = document.querySelector('input[name="UF_COORDS_x"]');
        const inputY = document.querySelector('input[name="UF_COORDS_y"]');
        const inputMain = document.querySelector('input[name="UF_COORDS"]');
        const updateMainField = function() {
            inputMain.value = `${inputX.value}|${inputY.value}`;
        }

        if (inputX && inputY && inputMain) {
            inputX.addEventListener('blur', updateMainField);
            inputY.addEventListener('blur', updateMainField);
        }
    }
);
