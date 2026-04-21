{{-- resources/views/components/calculator.blade.php --}}
@if(auth()->check() && auth()->user()->role === 'expense_manager')
<div class="calc-card shadow-[0_40px_100px_rgba(0,0,0,0.3)] overflow-hidden flex flex-col">

    <div class="display-section">
        <div class="brand-header flex justify-between items-center">

            <span id="calc-clock">--:--</span>
        </div>
        <div class="screen-output">
            <div id="history"></div>
            <div id="current-val">0</div>
        </div>
        <div class="ocean">
            <div class="wave"></div>
            <div class="wave"></div>
        </div>
    </div>
        <div class="keypad">
            <button class="clear" onclick="clearDisplay()">C</button>
            <button class="op" onclick="appendOp('*')">×</button>
            <button class="op" onclick="appendOp('/')">÷</button>
            <button class="op" onclick="backspace()">&larr;</button>

            <button onclick="appendNum('7')">7</button>
            <button onclick="appendNum('8')">8</button>
            <button onclick="appendNum('9')">9</button>
            <button class="op" onclick="appendOp('-')">−</button>

            <button onclick="appendNum('4')">4</button>
            <button onclick="appendNum('5')">5</button>
            <button onclick="appendNum('6')">6</button>
            <button class="op" onclick="appendOp('+')">+</button>

            <button onclick="appendNum('1')">1</button>
            <button onclick="appendNum('2')">2</button>
            <button onclick="appendNum('3')">3</button>
            <button class="equal" onclick="calculate()">=</button>

            <button onclick="appendNum('.')">.</button>
            <button onclick="appendNum('0')">0</button>
            <button onclick="appendOp('%')">%</button>
        </div>

</div>

<style>
    .calc-card {
        width: 320px;
        height: 540px;
        background-color: #ea258e;
        border-radius: 40px;
        z-index: 100;
        font-family: 'Outfit', sans-serif;
    }
    .display-section {
        flex: 1;
        padding: 30px 25px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        z-index: 2;
    }
    #calc-clock { color: rgba(255,255,255,0.6); font-size: 12px; }
    #current-val {
        font-size: 48px;
        font-weight: 300;
        color: white;
        text-align: right;
        letter-spacing: -1px;
        transition: font-size 0.2s;
    }
    #history { font-size: 16px; color: rgba(255,255,255,0.5); text-align: right; min-height: 24px; }

    .ocean { height: 80px; width: 100%; position: absolute; bottom: 45%; left: 0; pointer-events: none; overflow: hidden; }
    .wave {
        background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/85486/wave.svg') repeat-x;
        position: absolute;
        top: -198px;
        width: 6400px;
        height: 198px;
        animation: wave 7s infinite linear;
        opacity: 0.1;
    }
    @keyframes wave { 0% { transform: translateX(0); } 100% { transform: translateX(-1600px); } }

    .keypad {
        background: #ffffff;
        height: 60%;
        border-top-left-radius: 40px;
        border-top-right-radius: 40px;
        padding: 30px 20px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        z-index: 3;
        position: relative;
    }
    .keypad::before {
        content: '';
        position: absolute;
        top: 14px;
        left: 50%;
        transform: translateX(-50%);
        width: 36px;
        height: 4px;
        background: #e0e0e0;
        border-radius: 10px;
    }
    .keypad button {
        border: none;
        background: transparent;
        font-size: 20px;
        font-weight: 500;
        color: #2d3436;
        cursor: pointer;
        border-radius: 15px;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .keypad button:hover { background: #f8f9fa; transform: translateY(-2px); }
    .keypad button:active { transform: scale(0.9); background: #eef2ff; }
    .op { color: #4c6ef5; font-weight: 600; font-size: 22px; }
    .clear { color: #ff6b6b; }
    .equal {
        background: #ea258e !important;
        color: white !important;
        grid-row: span 2;
        box-shadow: 0 10px 20px rgba(234, 37, 142, 0.3);
        font-size: 24px !important;
    }
    .equal:hover { filter: brightness(1.1); box-shadow: 0 15px 30px rgba(234, 37, 142, 0.4); }

    [x-cloak] { display: none !important; }
</style>

<script>
(function () {
    let expression = "";
    let justCalculated = false; // ← tracks if = was just pressed

    const display     = document.getElementById('current-val');
    const historyEl   = document.getElementById('history');

    function updateDisplay() {
        if (!display) return;
        // Show only the last number being typed for cleanliness
        const parts = expression.split(/[\+\-\*\/]/);
        const last  = parts[parts.length - 1];
        display.innerText        = last || expression || "0";
        display.style.fontSize   = (last || expression).length > 10 ? "28px" : "48px";
    }

    window.appendNum = function (num) {
        // If user types a digit right after =, start fresh
        if (justCalculated) {
            expression     = "";
            historyEl.innerText = "";
            justCalculated = false;
        }

        // Prevent multiple decimals in same number segment
        const parts = expression.split(/[\+\-\*\/]/);
        const last  = parts[parts.length - 1];
        if (num === "." && last.includes(".")) return;

        expression += num;
        updateDisplay();
        navigator.vibrate?.(10);
    };

    window.appendOp = function (op) {
        if (expression === "" && op !== "-") return; // no leading operators except minus

        justCalculated = false; // allow chaining after =

        const lastChar = expression.slice(-1);

        // Replace trailing operator instead of stacking
        if ("+-*/".includes(lastChar)) {
            expression = expression.slice(0, -1);
        }

        // Show full expression in history while typing
        historyEl.innerText = expression + " " + opSymbol(op);

        expression += op;
        updateDisplay();
    };

    window.clearDisplay = function () {
        expression          = "";
        justCalculated      = false;
        historyEl.innerText = "";
        display.innerText   = "0";
        display.style.fontSize = "48px";
    };

    window.backspace = function () {
        if (justCalculated) {
            // Clear everything on backspace after result
            window.clearDisplay();
            return;
        }
        expression = expression.slice(0, -1);
        updateDisplay();
    };

    window.calculate = function () {
        if (!expression) return;

        let expr = expression
            .replace(/×/g, "*")
            .replace(/÷/g, "/")
            .replace(/%/g, "/100");

        // Strip trailing operator
        expr = expr.replace(/[\+\-\*\/]$/, "");

        let result;
        try {
            result = Function('"use strict"; return (' + expr + ')')();
            // Round floating point noise (e.g. 0.1+0.2 = 0.3 not 0.30000000004)
            result = parseFloat(result.toFixed(10));
        } catch {
            display.innerText   = "Error";
            historyEl.innerText = "";
            expression          = "";
            return;
        }

        historyEl.innerText    = expression + " =";
        expression             = String(result);
        justCalculated         = true;

        display.innerText      = expression;
        display.style.fontSize = expression.length > 10 ? "28px" : "48px";
    };

    function opSymbol(op) {
        return { "+": "+", "-": "−", "*": "×", "/": "÷", "%": "%" }[op] || op;
    }

    // Clock
    setInterval(() => {
        const el = document.getElementById('calc-clock');
        if (el) el.innerText = new Date().toLocaleTimeString('en-US', {
            hour: 'numeric', minute: '2-digit', hour12: true
        });
    }, 1000);

})();
</script>
@endif
