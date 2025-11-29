import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Heart, Mail, Lock, User, Phone, MapPin, ArrowRight } from "lucide-react";
import { Link, useLocation } from "wouter";
import { useEffect } from "react";
import { useState } from "react";

export default function Signup() {
  const [cities, setCities] = useState<{ id: number; name: string }[]>([]);
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    city: "",
    password: "",
    confirmPassword: "",
    userType: "volunteer",
  });
  const [loading, setLoading] = useState(false);

  // جلب المدن عند تحميل المكون
  useEffect(() => {
    const fetchCities = async () => {
      try {
        const response = await fetch("http://localhost:8000/api/cities");
        const data = await response.json();
        if (response.ok) {
          setCities(data.cities);
        } else {
          console.error("Failed to fetch cities:", data);
        }
      } catch (error) {
        console.error("Error fetching cities:", error);
      }
    };
    fetchCities();
  }, []);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

    const [location, setLocation] = useLocation();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (formData.password !== formData.confirmPassword) {
      alert("كلمات المرور غير متطابقة!");
      return;
    }
    setLoading(true);
    try {
      const { confirmPassword, phone, city, userType, ...dataToSend } = formData;
      
      // تحويل نوع المستخدم من 'organization' إلى 'charity' ليتوافق مع الواجهة الخلفية
      const finalUserType = userType === 'organization' ? 'charity' : 'volunteer';

      const response = await fetch("http://localhost:8000/api/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          ...dataToSend,
          user_type: finalUserType,
          city_id: city, // نفترض أن القيمة المخزنة في 'city' هي الـ ID
        }),
      });

      const data = await response.json();

      if (response.ok) {
        localStorage.setItem("authToken", data.token);
        alert("تم إنشاء الحساب بنجاح!");
        setLocation("/"); // إعادة التوجيه بعد التسجيل
      } else {
        // عرض رسائل الخطأ من الواجهة الخلفية
        const errorMessages = Object.values(data.errors || {}).flat().join("\n");
        alert(data.message || "فشل التسجيل. يرجى التحقق من البيانات.\n" + errorMessages);
      }
    } catch (error) {
      console.error("Registration error:", error);
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
            لديك حساب بالفعل؟{" "}
            <Link href="/login" className="text-primary font-semibold hover:underline">
              تسجيل الدخول
            </Link>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <div className="flex-1 flex items-center justify-center py-12 px-4">
        <Card className="w-full max-w-2xl">
          <CardHeader className="text-center">
            <CardTitle className="text-2xl">انضم إلى GiveHub</CardTitle>
            <CardDescription>
              أنشئ حسابك وابدأ رحلتك في التطوع والعطاء
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              {/* User Type Selection */}
              <div className="space-y-2">
                <label className="text-sm font-medium text-foreground">
                  نوع الحساب
                </label>
                <div className="grid grid-cols-2 gap-3">
                  <label className="flex items-center gap-2 p-3 border border-border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input
                      type="radio"
                      name="userType"
                      value="volunteer"
                      checked={formData.userType === "volunteer"}
                      onChange={handleChange}
                      className="w-4 h-4"
                    />
                    <span className="text-sm">متطوع</span>
                  </label>
                  <label className="flex items-center gap-2 p-3 border border-border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input
                      type="radio"
                      name="userType"
                      value="organization"
                      checked={formData.userType === "organization"}
                      onChange={handleChange}
                      className="w-4 h-4"
                    />
                    <span className="text-sm">منظمة</span>
                  </label>
                </div>
              </div>

              {/* Name Field */}
              <div className="space-y-2">
                <label className="text-sm font-medium text-foreground">
                  الاسم الكامل
                </label>
                <div className="relative">
                  <User className="absolute right-3 top-3 w-5 h-5 text-muted-foreground" />
                  <input
                    type="text"
                    name="name"
                    placeholder="أحمد محمد"
                    value={formData.name}
                    onChange={handleChange}
                    className="w-full pl-4 pr-10 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                  />
                </div>
              </div>

              {/* Email Field */}
              <div className="space-y-2">
                <label className="text-sm font-medium text-foreground">
                  البريد الإلكتروني
                </label>
                <div className="relative">
                  <Mail className="absolute right-3 top-3 w-5 h-5 text-muted-foreground" />
                  <input
                    type="email"
                    name="email"
                    placeholder="your@email.com"
                    value={formData.email}
                    onChange={handleChange}
                    className="w-full pl-4 pr-10 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                  />
                </div>
              </div>

              {/* Grid for Phone and City */}
              <div className="grid md:grid-cols-2 gap-4">
                {/* Phone Field */}
                <div className="space-y-2">
                  <label className="text-sm font-medium text-foreground">
                    رقم الهاتف
                  </label>
                  <div className="relative">
                    <Phone className="absolute right-3 top-3 w-5 h-5 text-muted-foreground" />
                    <input
                      type="tel"
                      name="phone"
                      placeholder="+966501234567"
                      value={formData.phone}
                      onChange={handleChange}
                      className="w-full pl-4 pr-10 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                      required
                    />
                  </div>
                </div>

                {/* City Field */}
                <div className="space-y-2">
                  <label className="text-sm font-medium text-foreground">
                    المدينة
                  </label>
                  <div className="relative">
                    <MapPin className="absolute right-3 top-3 w-5 h-5 text-muted-foreground" />
                    <select
                      name="city"
                      value={formData.city}
                      onChange={handleChange}
                      className="w-full pl-4 pr-10 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary appearance-none"
                      required
                    >
	                      <option value="">اختر المدينة</option>
	                      {cities.map((city) => (
	                        <option key={city.id} value={city.id}>
	                          {city.name}
	                        </option>
	                      ))}
                    </select>
                  </div>
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
                    name="password"
                    placeholder="••••••••"
                    value={formData.password}
                    onChange={handleChange}
                    className="w-full pl-4 pr-10 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                  />
                </div>
              </div>

              {/* Confirm Password Field */}
              <div className="space-y-2">
                <label className="text-sm font-medium text-foreground">
                  تأكيد كلمة المرور
                </label>
                <div className="relative">
                  <Lock className="absolute right-3 top-3 w-5 h-5 text-muted-foreground" />
                  <input
                    type="password"
                    name="confirmPassword"
                    placeholder="••••••••"
                    value={formData.confirmPassword}
                    onChange={handleChange}
                    className="w-full pl-4 pr-10 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                  />
                </div>
              </div>

              {/* Terms Checkbox */}
              <label className="flex items-start gap-2">
                <input
                  type="checkbox"
                  className="w-4 h-4 rounded border-border mt-1"
                  required
                />
                <span className="text-xs text-muted-foreground">
                  أوافق على{" "}
                  <a href="#" className="text-primary hover:underline">
                    شروط الخدمة
                  </a>
                  {" "}و{" "}
                  <a href="#" className="text-primary hover:underline">
                    سياسة الخصوصية
                  </a>
                </span>
              </label>

              {/* Submit Button */}
              <Button
                type="submit"
                className="w-full"
                disabled={loading}
              >
                {loading ? "جاري الإنشاء..." : "إنشاء الحساب"}
                {!loading && <ArrowRight className="w-4 h-4 ml-2" />}
              </Button>
            </form>
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
