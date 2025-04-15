<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Calculadora Matemática</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.8.0/math.min.js"></script>

    <style>
        body {
            background-color: #f7f7f7;
            padding-top: 40px;
            font-family: 'Segoe UI', sans-serif;
        }

        .calculator {
            max-width: 400px;
            margin: auto;
        }

        .display {
            background-color: #222;
            color: #0f0;
            font-size: 2rem;
            padding: 15px;
            text-align: right;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .btn {
            font-size: 1.2rem !important;
        }

        .mode-toggle {
            text-align: center;
            margin-bottom: 10px;
        }

        .history-box {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .history-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .history-item {
            font-size: 1rem;
            border-bottom: 1px solid #ccc;
            padding: 5px 0;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Calculadora com Variável x -->
            <div class="col-md-6">
                <div class="card p-4 shadow">
                    <h2 class="text-center text-primary mb-4">Calculadora com Variável x</h2>
                    <form action="processa.php" method="post">
                        <div class="mb-3">
                            <label for="expressao" class="form-label">Expressão matemática</label>
                            <input type="text" class="form-control" name="expressao" id="expressao"
                                placeholder="Ex: x**2 + 3*x" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de cálculo</label>
                            <select name="tipo" class="form-select">
                                <option value="normal">Resultado Numérico (com valor de x)</option>
                                <option value="derivada">Derivada</option>
                                <option value="integral">Integral</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="valor_x" class="form-label">Valor de x <small class="text-muted">(opcional)</small></label>
                            <input type="number" step="any" class="form-control" name="valor_x" id="valor_x" placeholder="Ex: 2">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Calcular</button>
                    </form>
                </div>
            </div>

            <!-- Calculadora Científica -->
            <div class="calculator card p-4 shadow-lg col-md-6">
                <div class="display" id="display">0</div>

                <!-- Alternador RAD/DEG -->
                <div class="mode-toggle">
                    <button class="btn btn-outline-secondary" onclick="alternarModo()">Modo: <span id="modoAtual">DEG</span></button>
                    <button class="btn btn-outline-primary ms-2" onclick="mostrarHistorico()">Histórico</button>
                    <button class="btn btn-outline-success ms-2" onclick="mostrarFracao()">Resultado Exato</button>

                </div>

                <div class="row g-2">
                    <!-- Botões padrão -->
                    <div class="col-3"><button class="btn btn-danger w-100" onclick="limpar()">C</button></div>
                    <div class="col-3"><button class="btn btn-secondary w-100" onclick="apagar()">⌫</button></div>
                    <div class="col-3"><button class="btn btn-warning w-100" onclick="inserir('(')">(</button></div>
                    <div class="col-3"><button class="btn btn-warning w-100" onclick="inserir(')')">)</button></div>

                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('7')">7</button></div>
                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('8')">8</button></div>
                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('9')">9</button></div>
                    <div class="col-3"><button class="btn btn-info w-100" onclick="inserir('/')">÷</button></div>

                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('4')">4</button></div>
                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('5')">5</button></div>
                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('6')">6</button></div>
                    <div class="col-3"><button class="btn btn-info w-100" onclick="inserir('*')">×</button></div>

                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('1')">1</button></div>
                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('2')">2</button></div>
                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('3')">3</button></div>
                    <div class="col-3"><button class="btn btn-info w-100" onclick="inserir('-')">−</button></div>

                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('0')">0</button></div>
                    <div class="col-3"><button class="btn btn-light w-100" onclick="inserir('.')">.</button></div>
                    <div class="col-3"><button class="btn btn-success w-100" onclick="calcular()">=</button></div>
                    <div class="col-3"><button class="btn btn-info w-100" onclick="inserir('+')">+</button></div>

                    <!-- Atualizado: xʸ insere '**' -->
                    <div class="col-4"><button class="btn btn-outline-dark w-100" onclick="inserir('**')">xʸ</button></div>
                    <div class="col-4"><button class="btn btn-outline-dark w-100" onclick="inserir('sqrt(')">√</button></div>
                    <div class="col-4"><button class="btn btn-outline-dark w-100" onclick="inserir('Math.PI')">π</button></div>
                    <div class="col-4"><button class="btn btn-outline-dark w-100" onclick="inserir('sin(')">sin</button></div>
                    <div class="col-4"><button class="btn btn-outline-dark w-100" onclick="inserir('cos(')">cos</button></div>
                    <div class="col-4"><button class="btn btn-outline-dark w-100" onclick="inserir('tan(')">tan</button></div>
                </div>

                <!-- Histórico -->
                <div class="history-box d-none" id="historico">
                    <div class="history-title">Histórico:</div>
                    <div id="listaHistorico"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let display = document.getElementById("display");
        let modo = "DEG";

        function inserir(valor) {
            if (display.innerText === "0") {
                display.innerText = "";
            }
            display.innerText += valor;
        }

        function limpar() {
            display.innerText = "0";
        }

        function apagar() {
            display.innerText = display.innerText.slice(0, -1) || "0";
        }

        function alternarModo() {
            modo = modo === "DEG" ? "RAD" : "DEG";
            document.getElementById("modoAtual").innerText = modo;
        }

        function calcular() {
            try {
                let expressao = display.innerText;
                expressao = expressao.replace(/(\d+),(\d+)/g, "$1.$2")
                    .replace(/π/g, "Math.PI")
                    .replace(/sqrt\(/g, "Math.sqrt(");

                if (modo === "DEG") {
                    expressao = expressao
                        .replace(/sin\(([^)]+)\)/g, (_, ang) => `Math.sin((${ang}) * Math.PI / 180)`)
                        .replace(/cos\(([^)]+)\)/g, (_, ang) => `Math.cos((${ang}) * Math.PI / 180)`)
                        .replace(/tan\(([^)]+)\)/g, (_, ang) => `Math.tan((${ang}) * Math.PI / 180)`);
                } else {
                    expressao = expressao
                        .replace(/sin/g, "Math.sin")
                        .replace(/cos/g, "Math.cos")
                        .replace(/tan/g, "Math.tan");
                }

                const resultado = eval(expressao);
                if (Number.isFinite(resultado)) {
                    adicionarAoHistorico(display.innerText + " = " + resultado);
                    display.innerText = resultado;
                } else {
                    display.innerText = "Erro";
                }
            } catch {
                display.innerText = "Erro";
            }
        }

        function adicionarAoHistorico(texto) {
            let historico = JSON.parse(localStorage.getItem("historico")) || [];
            historico.unshift(texto);
            if (historico.length > 20) historico.pop();
            localStorage.setItem("historico", JSON.stringify(historico));
            mostrarHistorico();
        }

        function mostrarHistorico() {
            const box = document.getElementById("historico");
            const lista = document.getElementById("listaHistorico");
            const dados = JSON.parse(localStorage.getItem("historico")) || [];

            lista.innerHTML = dados.map(item => `<div class="history-item">${item}</div>`).join("");
            box.classList.toggle("d-none");
        }

        function mostrarFracao() {
            try {
                const valor = eval(display.innerText);
                const fracao = math.fraction(valor);
                const formatado = `${fracao.n}/${fracao.d}`;
                adicionarAoHistorico(`${valor} = ${formatado}`);
                display.innerText = formatado;
            } catch {
                display.innerText = "Erro";
            }
        }
    </script>
</body>

</html>