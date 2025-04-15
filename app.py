from sympy import symbols, sympify, integrate, diff, latex
from flask import Flask, request, jsonify
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

x = symbols("x")


@app.route("/calcular", methods=["POST"])
def calcular():
    dados = request.json
    tipo = dados.get("tipo")
    expressao = dados.get("expressao")
    valor_x = dados.get("valor_x", None)

    try:
        expr = sympify(expressao)
        passos = []
        resultado_decimal = None

        if tipo == "integral":
            passos.append(f"∫ {latex(expr)} \\,dx")
            resultado = integrate(expr, x)
            passos.append(f"= {latex(resultado)} + C")

        elif tipo == "derivada":
            passos.append(f"\\frac{{d}}{{dx}}\\left({latex(expr)}\\right)")
            resultado = diff(expr, x)
            passos.append(f"= {latex(resultado)}")

        elif tipo == "normal":
            if valor_x is not None:
                expr_substituido = expr.subs(x, float(valor_x))
                resultado = expr_substituido.evalf()
                resultado_decimal = float(resultado)
                passos.append(f"Substituindo x = {valor_x}: {latex(expr_substituido)}")
                passos.append(f"Resultado numérico: {latex(resultado)}")
            else:
                return (
                    jsonify(
                        {"erro": "Para cálculo numérico, o valor de x é obrigatório."}
                    ),
                    400,
                )

        resposta = {"resultado_exato": latex(resultado), "passos": passos}

        if resultado_decimal is not None:
            resposta["resultado_decimal"] = resultado_decimal

        return jsonify(resposta)

    except Exception as e:
        return jsonify({"erro": str(e)}), 400


if __name__ == "__main__":
    app.run(debug=True)
