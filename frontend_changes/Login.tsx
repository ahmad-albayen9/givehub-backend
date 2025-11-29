import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Heart, Mail, Lock, ArrowRight } from "lucide-react";
import { Link, useLocation } from "wouter";
import { useState } from "react";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);

  const [location, setLocation] = useLocation();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
        try {
      const response = await fetch("http://localhost:8000/api/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();

      if (response.ok) {
        // تخزين التوكن في مكان ما (مثلاً localStorage)
        localStorage.setItem("authToken", data.token);
        alert("تم تسجيل الدخول بنجاح!");
        // إعادة التوجيه إلى الصفحة الرئيسية أو لوحة التحكم
        setLocation("/"); 
      } else {
        alert(data.message || "فشل تسجيل الدخول. يرجى التحقق من البيانات.");
      }
    } catch (error) {
      console.error("Login error:", error);
      alert("حدث خطأ أثناء الاتصال بالخادم.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-primary/10 to-primary/5 flex flex-col">
      {/* Navigation */}
      <nav className="bg-white border-b border-border">
        <div className="container flex items-center justify-between h-16">
          <Link href="/" className="flex items-center gap-2">
            <Heart className="w-6 h-6 text-primary fill-primary" />
            <span className="text-xl font-bold text-foreground">GiveHub</span>
          </Link>
          <div className="text-sm text-muted-foreground">
            ليس لديك حساب؟{" "}
            <Link href="/signup" className="text-primary font-semibold hover:underline">
              انضم الآن
            </Link>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <div className="flex-1 flex items-center justify-center py-12 px-4">
        <Card className="w-full max-w-md">
          <CardHeader className="text-center">
            <CardTitle className="text-2xl">تسجيل الدخول</CardTitle>
            <CardDescription>
              أدخل بيانات حسابك للوصول إلى منصة GiveHub
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              {/* Email Field */}
              <div className="space-y-2">
                <label className="text-sm font-medium text-foreground">
                  البريد الإلكتروني
                </label>
                <div className="relative">
                  <Mail className="absolute right-3 top-3 w-5 h-5 text-muted-foreground" />
                  <input
                    type="email"
                    placeholder="your@email.com"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="w-full pl-4 pr-10 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                  />
                </div>
              </div>

              {/* Password Field */}
              <div className="space-y-2">
                <label className="text-sm font-medium text-foreground">
                  كلمة المرور
                </label>
                <div className="relative">
                  <Lock className="absolute right-3 top-3 w-5 h-5 text-muted-foreground" />
                  <input
                    type="password"
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="w-full pl-4 pr-10 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                  />
                </div>
              </div>

              {/* Remember Me & Forgot Password */}
              <div className="flex items-center justify-between text-sm">
                <label className="flex items-center gap-2">
                  <input
                    type="checkbox"
                    className="w-4 h-4 rounded border-border"
                  />
                  <span className="text-muted-foreground">تذكرني</span>
                </label>
                <a href="#" className="text-primary hover:underline">
                  هل نسيت كلمة المرور؟
                </a>
              </div>

              {/* Submit Button */}
              <Button
                type="submit"
                className="w-full"
                disabled={loading}
              >
                {loading ? "جاري التحميل..." : "تسجيل الدخول"}
                {!loading && <ArrowRight className="w-4 h-4 ml-2" />}
              </Button>
            </form>

            {/* Divider */}
            <div className="relative my-6">
              <div className="absolute inset-0 flex items-center">
                <div className="w-full border-t border-border"></div>
              </div>
              <div className="relative flex justify-center text-sm">
                <span className="px-2 bg-white text-muted-foreground">أو</span>
              </div>
            </div>

            {/* Social Login */}
            <div className="space-y-2">
              <Button variant="outline" className="w-full">
                <span>🔵</span>
                <span className="ml-2">تسجيل الدخول عبر Facebook</span>
              </Button>
              <Button variant="outline" className="w-full">
                <span>🔴</span>
                <span className="ml-2">تسجيل الدخول عبر Google</span>
              </Button>
            </div>

            {/* Terms */}
            <p className="text-xs text-muted-foreground text-center mt-4">
              بتسجيل دخولك، فإنك توافق على{" "}
              <a href="#" className="text-primary hover:underline">
                شروط الخدمة
              </a>
              {" "}و{" "}
              <a href="#" className="text-primary hover:underline">
                سياسة الخصوصية
              </a>
            </p>
          </CardContent>
        </Card>
      </div>

      {/* Footer */}
      <footer className="bg-white border-t border-border py-6">
        <div className="container text-center text-sm text-muted-foreground">
          <p>&copy; 2024 GiveHub. جميع الحقوق محفوظة.</p>
        </div>
      </footer>
    </div>
  );
}
