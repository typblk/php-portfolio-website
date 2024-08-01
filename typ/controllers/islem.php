<?php
class Tayyip extends Db
{
    // trafik
    public function logTraffic($page)
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $pageVisited = $page;
        $timestamp = date('Y-m-d H:i:s'); // Tarih ve saat bilgisi

        $sql = "INSERT INTO trafik (ip_address, page_visited, created_at) VALUES (:ip_address, :page_visited, :created_at)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':ip_address', $ipAddress);
        $stmt->bindParam(':page_visited', $pageVisited);
        $stmt->bindParam(':created_at', $timestamp);
        $stmt->execute();
    }

    public function getTrafficByMonth()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS count
            FROM trafik
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
            GROUP BY month
            ORDER BY month";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ay isimlerini Türkçe'ye dönüştür
        $monthNames = [
            '01' => 'Oca',
            '02' => 'Şub',
            '03' => 'Mar',
            '04' => 'Nis',
            '05' => 'May',
            '06' => 'Haz',
            '07' => 'Tem',
            '08' => 'Ağu',
            '09' => 'Eyl',
            '10' => 'Eki',
            '11' => 'Kas',
            '12' => 'Ara'
        ];

        foreach ($data as &$row) {
            $monthNum = substr($row['month'], 5, 2);
            $row['month'] = $monthNames[$monthNum];
        }

        return $data;
    }

    public function getTrafikSayisi()
    {
        $sql = "SELECT COUNT(*) FROM trafik";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    // hizmetler
    public function createHizmet($hizmet, $fotograf, $kisa_aciklama, $aciklama, $hizmetUrl)
    {
        $sql = "INSERT INTO hizmetler(hizmet,fotograf,kisa_aciklama,aciklama,hizmetUrl) VALUES (:hizmet,:fotograf,:kisa_aciklama,:aciklama,:hizmetUrl)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'hizmet' => $hizmet,
            'fotograf' => $fotograf,
            'kisa_aciklama' => $kisa_aciklama,
            'aciklama' => $aciklama,
            'hizmetUrl' => $hizmetUrl
        ]);
    }

    public function getHizmetById(int $id)
    {
        $sql = "SELECT * from hizmetler WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getHizmetSlug(string $hizmetUrl)
    {
        $sql = "SELECT * from hizmetler WHERE hizmetUrl=:hizmetUrl";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['hizmetUrl' => $hizmetUrl]);
        return $stmt->fetch();
    }

    public function getHizmet()
    {
        $sql = "SELECT * from hizmetler";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function editHizmet($id, $hizmet, $fotograf, $kisa_aciklama, $aciklama, $hizmetUrl)
    {
        $sql = "UPDATE hizmetler SET hizmet=:hizmet, fotograf=:fotograf, kisa_aciklama=:kisa_aciklama, aciklama=:aciklama, hizmetUrl=:hizmetUrl WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'hizmet' => $hizmet,
            'fotograf' => $fotograf,
            'kisa_aciklama' => $kisa_aciklama,
            'aciklama' => $aciklama,
            'hizmetUrl' => $hizmetUrl
        ]);
    }

    public function deleteHizmet($id)
    {
        $sql = "SELECT fotograf FROM hizmetler WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $fotografPath = '../images/' . $row['fotograf'];

        $deleteSql = "DELETE FROM hizmetler WHERE id=:id";
        $deleteStmt = $this->connect()->prepare($deleteSql);
        $deleteStmt->execute(['id' => $id]);

        if ($deleteStmt->rowCount() > 0) {
            if (file_exists($fotografPath)) {
                unlink($fotografPath);
            }

            return true;
        } else {
            return false;
        }
    }

    // projeler
    public function createProje($proje, $kategori, $fotograf, $teknoloji, $link, $kisa_aciklama, $aciklama, $projeUrl)
    {
        $sql = "INSERT INTO projeler(proje,kategori,fotograf,teknoloji,link,aciklama,kisa_aciklama,projeUrl) VALUES (:proje,:kategori,:fotograf,:teknoloji,:link,:kisa_aciklama,:aciklama,:projeUrl)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'proje' => $proje,
            'kategori' => $kategori,
            'fotograf' => $fotograf,
            'teknoloji' => $teknoloji,
            'link' => $link,
            'kisa_aciklama' => $kisa_aciklama,
            'aciklama' => $aciklama,
            'projeUrl' => $projeUrl
        ]);
    }

    public function getProjeById(int $id)
    {
        $sql = "SELECT * from projeler WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getProjeSlug(string $projeUrl)
    {
        $sql = "SELECT * from projeler WHERE projeUrl=:projeUrl";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['projeUrl' => $projeUrl]);
        return $stmt->fetch();
    }

    public function getProje()
    {
        $sql = "SELECT * from projeler";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProjeSayisi()
    {
        $sql = "SELECT COUNT(*) FROM projeler";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getProjeA()
    {
        $sql = "SELECT * from projeler ORDER BY id DESC LIMIT 9";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function editProje($id, $proje, $kategori, $fotograf, $teknoloji, $link, $kisa_aciklama, $aciklama, $projeUrl)
    {
        $sql = "UPDATE projeler SET proje=:proje, kategori=:kategori, fotograf=:fotograf, teknoloji=:teknoloji, link=:link, kisa_aciklama=:kisa_aciklama, aciklama=:aciklama, projeUrl=:projeUrl WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'proje' => $proje,
            'kategori' => $kategori,
            'fotograf' => $fotograf,
            'teknoloji' => $teknoloji,
            'link' => $link,
            'kisa_aciklama' => $kisa_aciklama,
            'aciklama' => $aciklama,
            'projeUrl' => $projeUrl
        ]);
    }

    public function deleteProje($id)
    {
        $sql = "SELECT fotograf FROM projeler WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $fotografPath = '../images/' . $row['fotograf'];

        $deleteSql = "DELETE FROM projeler WHERE id=:id";
        $deleteStmt = $this->connect()->prepare($deleteSql);
        $deleteStmt->execute(['id' => $id]);

        if ($deleteStmt->rowCount() > 0) {
            if (file_exists($fotografPath)) {
                unlink($fotografPath);
            }

            return true;
        } else {
            return false;
        }
    }

    // blog
    public function createBlog($baslik, $fotograf, $etiket, $kisa_aciklama, $blog, $tarih, $blogUrl)
    {
        $sql = "INSERT INTO bloglar(baslik,fotograf,etiket,kisa_aciklama,blog,tarih,blogUrl) VALUES (:baslik,:fotograf,:etiket,:kisa_aciklama,:blog,:tarih,:blogUrl)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'baslik' => $baslik,
            'fotograf' => $fotograf,
            'etiket' => $etiket,
            'kisa_aciklama' => $kisa_aciklama,
            'blog' => $blog,
            'tarih' => $tarih,
            'blogUrl' => $blogUrl
        ]);
    }

    public function getBlogById(int $id)
    {
        $sql = "SELECT * from bloglar WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getBlogSlug(string $blogUrl)
    {
        $sql = "SELECT * from bloglar WHERE blogUrl=:blogUrl";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['blogUrl' => $blogUrl]);
        return $stmt->fetch();
    }

    public function getBlog()
    {
        $sql = "SELECT * from bloglar";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBlogSayisi()
    {
        $sql = "SELECT COUNT(*) FROM bloglar";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getBlogA()
    {
        $sql = "SELECT * from bloglar ORDER BY id DESC LIMIT 3";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function editBlog($id, $baslik, $fotograf, $etiket, $kisa_aciklama, $blog, $tarih, $blogUrl)
    {
        $sql = "UPDATE bloglar SET baslik=:baslik, fotograf=:fotograf, etiket=:etiket, kisa_aciklama=:kisa_aciklama, blog=:blog, tarih=:tarih, blogUrl=:blogUrl WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'baslik' => $baslik,
            'fotograf' => $fotograf,
            'etiket' => $etiket,
            'kisa_aciklama' => $kisa_aciklama,
            'blog' => $blog,
            'tarih' => $tarih,
            'blogUrl' => $blogUrl
        ]);
    }

    public function deleteBlog($id)
    {
        $sql = "SELECT fotograf FROM bloglar WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $fotografPath = '../images/' . $row['fotograf'];

        $deleteSql = "DELETE FROM bloglar WHERE id=:id";
        $deleteStmt = $this->connect()->prepare($deleteSql);
        $deleteStmt->execute(['id' => $id]);

        if ($deleteStmt->rowCount() > 0) {
            if (file_exists($fotografPath)) {
                unlink($fotografPath);
            }

            return true;
        } else {
            return false;
        }
    }

    // slayt
    public function createSlayt($sayfa, $baslik, $aciklama, $fotograf)
    {
        $sql = "INSERT INTO slayt(sayfa, baslik, aciklama, fotograf) VALUES (:sayfa,:baslik,:aciklama,:fotograf)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'sayfa' => $sayfa,
            'baslik' => $baslik,
            'aciklama' => $aciklama,
            'fotograf' => $fotograf
        ]);
    }

    public function getSlaytById(int $id)
    {
        $sql = "SELECT * from slayt WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getSlaytSayfa(string $sayfa)
    {
        $sql = "SELECT * from slayt WHERE sayfa=:sayfa";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['sayfa' => $sayfa]);
        return $stmt->fetch();
    }

    public function getSlayt()
    {
        $sql = "SELECT * from slayt";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function editSlayt($id, $sayfa, $baslik, $aciklama, $fotograf)
    {
        $sql = "UPDATE slayt SET sayfa=:sayfa, baslik=:baslik, aciklama=:aciklama, fotograf=:fotograf WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'sayfa' => $sayfa,
            'baslik' => $baslik,
            'aciklama' => $aciklama,
            'fotograf' => $fotograf,
        ]);
    }

    public function deleteSlayt($id)
    {
        $sql = "SELECT fotograf FROM slayt WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $fotografPath = '../images/' . $row['fotograf'];

        $deleteSql = "DELETE FROM slayt WHERE id=:id";
        $deleteStmt = $this->connect()->prepare($deleteSql);
        $deleteStmt->execute(['id' => $id]);

        if ($deleteStmt->rowCount() > 0) {
            if (file_exists($fotografPath)) {
                unlink($fotografPath);
            }

            return true;
        } else {
            return false;
        }
    }

    //ayarlar
    public function getAyarlar()
    {
        $sql = "SELECT * from ayarlar LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function editGenelAyarlar($site_adi, $site_desc, $site_keyw, $logo, $favicon)
    {
        $sql = "UPDATE ayarlar SET site_adi=:site_adi, site_desc=:site_desc, site_keyw=:site_keyw, logo=:logo, favicon=:favicon";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'site_adi' => $site_adi,
            'site_desc' => $site_desc,
            'site_keyw' => $site_keyw,
            'logo' => $logo,
            'favicon' => $favicon,
        ]);
    }

    public function editIletisimAyarlar($email, $tel, $fotograf, $face, $insta, $x, $linke, $git, $tele, $you)
    {
        $sql = "UPDATE ayarlar SET email=:email, tel=:tel, fotograf=:fotograf, face=:face, insta=:insta, x=:x, linke=:linke, git=:git, tele=:tele, you=:you";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'email' => $email,
            'tel' => $tel,
            'fotograf' => $fotograf,
            'face' => $face,
            'insta' => $insta,
            'x' => $x,
            'linke' => $linke,
            'git' => $git,
            'tele' => $tele,
            'you' => $you
        ]);
    }

    public function editSeoAyarlar($site_url, $analytic, $alt_aciklama)
    {
        $sql = "UPDATE ayarlar SET site_url=:site_url, analytic=:analytic, alt_aciklama=:alt_aciklama";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'site_url' => $site_url,
            'analytic' => $analytic,
            'alt_aciklama' => $alt_aciklama
        ]);
    }

    public function editcv($cv)
    {
        $sql = "UPDATE ayarlar SET cv=:cv";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'cv' => $cv
        ]);
    }

    //hakkımızda
    public function getHakkimizda()
    {
        $sql = "SELECT * from hakkimizda LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function editHakkimizda($baslik, $fotograf, $hakkimizda)
    {
        $sql = "UPDATE hakkimizda SET baslik=:baslik, fotograf=:fotograf, hakkimizda=:hakkimizda";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'baslik' => $baslik,
            'fotograf' => $fotograf,
            'hakkimizda' => $hakkimizda,
        ]);
    }

    //gizlilik
    public function getGizlilik()
    {
        $sql = "SELECT * from politika LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function editGizlilik($gizlilik)
    {
        $sql = "UPDATE politika SET gizlilik=:gizlilik";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'gizlilik' => $gizlilik,
        ]);
    }

    //çerez
    public function getCerez()
    {
        $sql = "SELECT * from politika LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function editCerez($cerez)
    {
        $sql = "UPDATE politika SET cerez=:cerez";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'cerez' => $cerez,
        ]);
    }

    // yorumlar
    public function createYorum($yorum_yapan, $yorum, $fotograf)
    {
        $sql = "INSERT INTO yorumlar(yorum_yapan,yorum,fotograf) VALUES (:yorum_yapan,:yorum,:fotograf)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'yorum_yapan' => $yorum_yapan,
            'yorum' => $yorum,
            'fotograf' => $fotograf,
        ]);
    }

    public function getYorumById(int $id)
    {
        $sql = "SELECT * from yorumlar WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getYorum()
    {
        $sql = "SELECT * from yorumlar ORDER BY id DESC LIMIT 3";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRandomYorum($limit = 1)
    {
        $sql = "SELECT * FROM yorumlar ORDER BY RAND() LIMIT :limit";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function editYorum($id, $yorum_yapan, $yorum, $fotograf)
    {
        $sql = "UPDATE yorumlar SET yorum_yapan=:yorum_yapan, yorum=:yorum, fotograf=:fotograf WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'yorum_yapan' => $yorum_yapan,
            'yorum' => $yorum,
            'fotograf' => $fotograf,
        ]);
    }

    public function deleteYorum($id)
    {
        $sql = "SELECT fotograf FROM yorumlar WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $fotografPath = '../images/' . $row['fotograf'];

        $deleteSql = "DELETE FROM yorumlar WHERE id=:id";
        $deleteStmt = $this->connect()->prepare($deleteSql);
        $deleteStmt->execute(['id' => $id]);

        if ($deleteStmt->rowCount() > 0) {
            if (file_exists($fotografPath)) {
                unlink($fotografPath);
            }

            return true;
        } else {
            return false;
        }
    }

    // Ürün
    public function createUrun($baslik, $link, $fotograf, $kisa_aciklama, $aciklama, $urunUrl)
    {
        $sql = "INSERT INTO urunler(baslik,link,fotograf,kisa_aciklama,aciklama,urunUrl) VALUES (:baslik,:link,:fotograf,:kisa_aciklama,:aciklama,:urunUrl)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'baslik' => $baslik,
            'link' => $link,
            'fotograf' => $fotograf,
            'kisa_aciklama' => $kisa_aciklama,
            'aciklama' => $aciklama,
            'urunUrl' => $urunUrl,
        ]);
    }

    public function getUrunById(int $id)
    {
        $sql = "SELECT * from urunler WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getUrunSlug(string $urunUrl)
    {
        $sql = "SELECT * from urunler WHERE urunUrl=:urunUrl";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['urunUrl' => $urunUrl]);
        return $stmt->fetch();
    }

    public function getUrun()
    {
        $sql = "SELECT * from urunler";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function editUrun($id, $baslik, $link, $fotograf, $kisa_aciklama, $aciklama, $urunUrl)
    {
        $sql = "UPDATE urunler SET baslik=:baslik, link=:link, fotograf=:fotograf, kisa_aciklama=:kisa_aciklama, aciklama=:aciklama, urunUrl=:urunUrl WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'baslik' => $baslik,
            'link' => $link,
            'fotograf' => $fotograf,
            'kisa_aciklama' => $kisa_aciklama,
            'aciklama' => $aciklama,
            'urunUrl' => $urunUrl
        ]);
    }

    public function deleteUrun($id)
    {
        $sql = "SELECT fotograf FROM urunler WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $fotografPath = '../images/' . $row['fotograf'];

        $deleteSql = "DELETE FROM urunler WHERE id=:id";
        $deleteStmt = $this->connect()->prepare($deleteSql);
        $deleteStmt->execute(['id' => $id]);

        if ($deleteStmt->rowCount() > 0) {
            if (file_exists($fotografPath)) {
                unlink($fotografPath);
            }

            return true;
        } else {
            return false;
        }
    }

    // teknolojiler
    public function createTeknoloji($teknoloji, $logo)
    {
        $sql = "INSERT INTO teknolojiler(teknoloji,logo) VALUES (:teknoloji,:logo)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'teknoloji' => $teknoloji,
            'logo' => $logo,
        ]);
    }

    public function getTeknolojiById(int $id)
    {
        $sql = "SELECT * from teknolojiler WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getTeknoloji()
    {
        $sql = "SELECT * from teknolojiler";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function editTeknoloji($id, $teknoloji, $logo)
    {
        $sql = "UPDATE teknolojiler SET teknoloji=:teknoloji, logo=:logo WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'teknoloji' => $teknoloji,
            'logo' => $logo,
        ]);
    }

    public function deleteTeknoloji($id)
    {
        $sql = "SELECT logo FROM teknolojiler WHERE id=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $logoPath = '../images/' . $row['logo'];

        $deleteSql = "DELETE FROM teknolojiler WHERE id=:id";
        $deleteStmt = $this->connect()->prepare($deleteSql);
        $deleteStmt->execute(['id' => $id]);

        if ($deleteStmt->rowCount() > 0) {
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }

            return true;
        } else {
            return false;
        }
    }


    //kullanıcı

    public function getKullanici($username)
    {
        $sql = "SELECT * FROM kullanici WHERE email = :email LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':email', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
