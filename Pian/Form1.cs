using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Windows.Media;
using System.Windows.Input;
using System.Threading;
using System.Diagnostics;
using System.Threading.Tasks;
using System.IO;
namespace Pian
{
    public partial class Form1 : Form
    {
        public MediaPlayer sunet = new MediaPlayer();
        MediaPlayer[] p = new MediaPlayer[20];
        MediaPlayer p1 = new MediaPlayer();

        MediaPlayer p2 = new MediaPlayer();
        MediaPlayer p3 = new MediaPlayer();
        MediaPlayer p4 = new MediaPlayer();

        MediaPlayer C1 = new MediaPlayer();
        MediaPlayer C1S = new MediaPlayer();
        MediaPlayer D1 = new MediaPlayer();
        MediaPlayer D1S = new MediaPlayer();
        MediaPlayer E1 = new MediaPlayer();
        MediaPlayer F1 = new MediaPlayer();
        MediaPlayer F1S = new MediaPlayer();
        MediaPlayer G1 = new MediaPlayer();
        MediaPlayer G1S = new MediaPlayer();
        MediaPlayer A1 = new MediaPlayer();
        MediaPlayer A1S = new MediaPlayer();
        MediaPlayer B1 = new MediaPlayer();
        MediaPlayer C2 = new MediaPlayer();
        MediaPlayer C2S = new MediaPlayer();
        MediaPlayer D2 = new MediaPlayer();
        MediaPlayer D2S = new MediaPlayer();
        MediaPlayer E2 = new MediaPlayer();
        MediaPlayer F2 = new MediaPlayer();
        MediaPlayer F2S = new MediaPlayer();
        MediaPlayer G2 = new MediaPlayer();
        MediaPlayer G2S = new MediaPlayer();
        MediaPlayer A2 = new MediaPlayer();
        MediaPlayer A2S = new MediaPlayer();
        MediaPlayer B2 = new MediaPlayer();
        MediaPlayer C3 = new MediaPlayer();
        MediaPlayer bogat = new MediaPlayer();
        PictureBox[] imagini = new PictureBox[1000];
        int szi = 0;
        int aux1 = 0;
        public bool [] playing=new bool[50];
        bool recording = true;
        bool player2 = false;
        public bool motoare = false;
        public int beg = 1;
        int scor = 0;
        public bool[] pushed = new bool[100];
        

        public struct tasta_timp
        {
            public System.Windows.Forms.KeyEventArgs tasta;
            public long timp;
        }
        public Stopwatch sw=new Stopwatch();
        public tasta_timp[] d = new tasta_timp[1000];
        public int sz = 0;
        public int cnt = 0;
        public int dar = 1;
        public Form1()
        {
            InitializeComponent();
            timer1.Enabled = false;
            button1.Select();
            sw = Stopwatch.StartNew();
            for (int i = 0; i < 20; i++)
                playing[i] = false;
            for (int i = 0; i < 100; i++)
                pushed[i] = false;
            poza1.Visible = false;
            d[0].timp = 0;
            motoare = false;
            cnt = 0;
            beg = 1;
            label2.Parent = panel1;
            label2.BackColor = System.Drawing.Color.Transparent;
            label2.Text = "Bine ai venit!";
            label3.BackColor = System.Drawing.Color.Transparent;
            label_scor.BackColor= System.Drawing.Color.Transparent;
            Startbutton.ForeColor = System.Drawing.Color.LawnGreen;
            button2_back.ForeColor = System.Drawing.Color.LawnGreen;

        }
        public void adauga(System.Windows.Forms.KeyEventArgs tasta1,long timp1)
        {
            d[++sz].timp = timp1;
            d[sz].tasta = tasta1;
        }
        public async Task check(Button bt1,PictureBox pb1)
        {
            if (recording == true)
                return;
            panel1.Controls.Add(label2);                                                                                                                                                              
            if (bt1.Location.Y - pb1.Location.Y >= 50)
            {
                scor--;
                label2.ForeColor = System.Drawing.Color.Red;
                label2.Text = "Prea Devreme!";
                pb1.BackColor = System.Drawing.Color.IndianRed;
            }
            else if (bt1.Location.Y - pb1.Location.Y < -50)
            {
                scor--;
                label2.ForeColor = System.Drawing.Color.Red;
                label2.Text = "Prea Tarziu!";
                pb1.BackColor = System.Drawing.Color.IndianRed;     
            }
            else
            {
                scor++;
                label2.ForeColor = System.Drawing.Color.LawnGreen;
                label2.Text = "OK";
                pb1.BackColor = System.Drawing.Color.LawnGreen;
                if (pushed[szi-1] == true)
                {
                    timer1.Stop();
                    timer1.Enabled = false;
                    TimeSpan t1 = new TimeSpan(0, 0, 0, 0, 2000);
                    await Task.Delay(t1);
                    label2.Text = "Ai obtinut scorul:" + scor.ToString();
                    
                }
            }
            string s = scor.ToString();
            label_scor.Text = s;

        }
        public async void ruleaza()
        {
            int i,aux,aux1,dif;
            System.Windows.Forms.KeyEventArgs e;
            string s, s1;
            TimeSpan t=new TimeSpan(0,0,0,0,0);
            for (i=dar;i<=sz;i++)
            {
                e = d[i].tasta;
                dar++;
                s1 = d[i].timp.ToString();
                aux1 = Convert.ToInt32(s1);             
                if (i<sz)
                {
                    s = d[i + 1].timp.ToString();
                    aux = Convert.ToInt32(s);
                    dif = aux - aux1;
                    t = new TimeSpan(0, 0, 0, 0, dif);
                }
                
                imagini[++szi] = new PictureBox();
                imagini[szi].Image = poza1.Image;
                imagini[szi].Width = button1.Width;
                imagini[szi].Height = 30;
                imagini[szi].Enabled = true;
                imagini[szi].Visible = false;
                panel1.Controls.Add(imagini[szi]);
                
                if (e.KeyCode==Keys.Q)
                {
                    int x = button1.Location.X;
                    int y = button1.Location.Y - 205;

                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if(e.KeyCode==Keys.D2)
                {
                    int x = button2.Location.X;
                    int y = button2.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if(e.KeyCode==Keys.W)
                {
                    int x = button3.Location.X;
                    int y = button3.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if(e.KeyCode==Keys.D3)
                {
                    int x = button4.Location.X;
                    int y = button4.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.E)
                {
                    int x = button5.Location.X;
                    int y = button5.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.R)
                {
                    int x = button6.Location.X;
                    int y = button6.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.D5)
                {
                    int x = button7.Location.X;
                    int y = button7.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.T)
                {
                    int x = button8.Location.X;
                    int y = button8.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.D6)
                {
                    int x = button9.Location.X;
                    int y = button9.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.Y)
                {
                    int x = button10.Location.X;
                    int y = button10.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);

                }
                else if (e.KeyCode == Keys.D7)
                {
                    int x = button11.Location.X;
                    int y = button11.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.U)
                {
                    int x = button12.Location.X;
                    int y = button12.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.I)
                {
                    int x = button13.Location.X;
                    int y = button13.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.D9)
                {
                    int x = button14.Location.X;
                    int y = button14.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.O)
                {
                    int x = button15.Location.X;
                    int y = button15.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.D0)
                {
                    int x = button16.Location.X;
                    int y = button16.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.Z)
                {
                    int x = button17.Location.X;
                    int y = button17.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.X)
                {
                    int x = button18.Location.X;
                    int y = button18.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.D)
                {
                    int x = button19.Location.X;
                    int y = button19.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.C)
                {
                    int x = button20.Location.X;
                    int y = button20.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.F)
                {
                    int x = button21.Location.X;
                    int y = button21.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.V)
                {
                    int x = button22.Location.X;
                    int y = button22.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.G)
                {
                    int x = button23.Location.X;
                    int y = button23.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.B)
                {
                    int x = button24.Location.X;
                    int y = button24.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                else if (e.KeyCode == Keys.N)
                {
                    int x = button25.Location.X;
                    int y = button25.Location.Y - 205;
                    imagini[szi].Location = new Point(x, y);
                    imagini[szi].Visible = true;
                    panel1.Controls.Add(imagini[szi]);
                }
                await Task.Delay(t);
            }

        }
        public void verifica_adauga(System.Windows.Forms.KeyEventArgs e)
        {
            if (recording == true)
            {
                adauga(e, sw.ElapsedMilliseconds);
            }
            else
            functie(e);
        }
        public void functie(System.Windows.Forms.KeyEventArgs e)
        {
                cnt++;
                if (cnt <= szi)
                {
                    if (e.KeyCode == d[cnt].tasta.KeyCode)
                    {
                        if (e.KeyCode == Keys.Q)
                            check(button1, imagini[cnt]);
                        else if (e.KeyCode == Keys.D2)
                            check(button2, imagini[cnt]);
                        else if (e.KeyCode == Keys.W)
                            check(button3, imagini[cnt]);
                        else if (e.KeyCode == Keys.D3)
                            check(button4, imagini[cnt]);
                        else if (e.KeyCode == Keys.E)
                            check(button5, imagini[cnt]);
                        else if (e.KeyCode == Keys.R)
                            check(button6, imagini[cnt]);
                        else if (e.KeyCode == Keys.D5)
                            check(button7, imagini[cnt]);
                        else if (e.KeyCode == Keys.T)
                            check(button8, imagini[cnt]);
                        else if (e.KeyCode == Keys.D6)
                            check(button9, imagini[cnt]);
                        else if (e.KeyCode == Keys.Y)
                            check(button10, imagini[cnt]);
                        else if (e.KeyCode == Keys.D7)
                            check(button11, imagini[cnt]);
                        else if (e.KeyCode == Keys.U)
                            check(button12, imagini[cnt]);
                        else if (e.KeyCode == Keys.I)
                            check(button13, imagini[cnt]);
                        else if (e.KeyCode == Keys.D9)
                            check(button14, imagini[cnt]);
                        else if (e.KeyCode == Keys.O)
                            check(button15, imagini[cnt]);
                        else if (e.KeyCode == Keys.D0)
                            check(button16, imagini[cnt]);
                        else if (e.KeyCode == Keys.Z)
                            check(button17, imagini[cnt]);
                        else if (e.KeyCode == Keys.X)
                            check(button18, imagini[cnt]);
                        else if (e.KeyCode == Keys.D)
                            check(button19, imagini[cnt]);
                        else if (e.KeyCode == Keys.C)
                            check(button20, imagini[cnt]);
                        else if (e.KeyCode == Keys.F)
                            check(button21, imagini[cnt]);
                        else if (e.KeyCode == Keys.V)
                            check(button22, imagini[cnt]);
                        else if (e.KeyCode == Keys.G)
                            check(button23, imagini[cnt]);
                        else if (e.KeyCode == Keys.B)
                            check(button24, imagini[cnt]);
                        else if (e.KeyCode == Keys.N)
                            check(button25, imagini[cnt]);
                    timer1.Start();
                    pushed[cnt] = true;
                    }
                    else
                    {
                        imagini[cnt].BackColor = System.Drawing.Color.Red;
                        label1.ForeColor = System.Drawing.Color.Red;
                        label1.Text = "Ups! Ai gresit nota!";
                        cnt--;
                        scor--;
                        pushed[cnt] = false;
                        timer1.Stop();
                    }
                string s = scor.ToString();
                label_scor.Text = s;
            }
        }
        public void button1_KeyDown(object sender, System.Windows.Forms.KeyEventArgs e)
        {
            if(e.KeyCode==Keys.F1)
            {
                if (!playing[41])
                {
                    bogat.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\bogat.wav"));
                          
                    
                    bogat.Play();
                    playing[41] = true;
                }
                else
                {
                    playing[41] = false;
                    bogat.Stop();
                }
            }
            if (e.KeyCode == Keys.D1 && !playing[40])
            {
                sunet.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\avalansa.wav"));
                sunet.Play();
                playing[40] = true;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.Q && !playing[1])
            {
                C1.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\C1.wav"));
                C1.Play();
                playing[1] = true;
                button1.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.D2 && !playing[2])
            {
                C1S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\C1#.wav"));
                C1S.Play();
                playing[2] = true;
                button2.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.W && !playing[3])
            {
                D1.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\D1.wav"));
                D1.Play();
                playing[3] = true;
                button3.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.D3 && !playing[4])
            {
                D1S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\D1#.wav"));
                D1S.Play();
                playing[4] = true;
                button4.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.E && !playing[5])
            {
                E1.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\E1.wav"));
                E1.Play();
                playing[5] = true;
                button5.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.R && !playing[6])
            {
                F1.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\F1.wav"));
                F1.Play();
                playing[6] = true;
                button6.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.D5 && !playing[7])
            {
                F1S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\F1#.wav"));
                F1S.Play();
                playing[7] = true;
                button7.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.T && !playing[8])
            {
                G1.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\G1.wav"));
                G1.Play();
                playing[8] = true;
                button8.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.D6 && !playing[9])
            {
                G1S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\G1#.wav"));
                G1S.Play();
                playing[9] = true;
                button9.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.Y && !playing[10])
            {
                A1.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\A1.wav"));
                A1.Play();
                playing[10] = true;
                button10.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.D7 && !playing[11])
            {
                A1S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\A1#.wav"));
                A1S.Play();
                playing[11] = true;
                button11.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.U && !playing[12])
            {
                B1.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\B1.wav"));
                B1.Play();
                playing[12] = true;
                button12.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.I && !playing[13])
            {
                C2.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\C2.wav"));
                C2.Play();
                playing[13] = true;
                button13.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.D9 && !playing[14])
            {
                C2S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\C2#.wav"));
                C2S.Play();
                playing[14] = true;
                button14.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.O && !playing[15])
            {
                D2.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\D2.wav"));
                D2.Play();
                playing[15] = true;
                button15.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.D0 && !playing[16])
            {
                D2S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\D2#.wav"));
                D2S.Play();
                playing[16] = true;
                button16.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.Z && !playing[17])
            {
                E2.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\E2.wav"));
                E2.Play();
                playing[17] = true;
                button17.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.X && !playing[18])
            {
                F2.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\F2.wav"));
                F2.Play();
                playing[18] = true;
                button18.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.D && !playing[19])
            {
                F2S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\F2#.wav"));
                F2S.Play();
                playing[19] = true;
                button19.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.C && !playing[20])
            {
                G2.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\G2.wav"));
                G2.Play();
                playing[20] = true;
                button20.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.F && !playing[21])
            {
                G2S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\G2#.wav"));
                G2S.Play();
                playing[21] = true;
                button21.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.V && !playing[22])
            {
                A2.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\A2.wav"));
                A2.Play();
                playing[22] = true;
                button22.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.G && !playing[23])
            {
                A2S.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\A2#.wav"));
                A2S.Play();
                playing[23] = true;
                button23.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.B && !playing[24])
            {
                B2.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\B2.wav"));
                B2.Play();
                playing[24] = true;
                button24.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
            else if (e.KeyCode == Keys.N && !playing[25])
            {
                C3.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\C3.wav"));
                C3.Play();
                playing[25] = true;
                button25.BackColor = System.Drawing.Color.LawnGreen;
                verifica_adauga(e);
            }
        }

        private void button1_KeyUp(object sender, System.Windows.Forms.KeyEventArgs e)
        {
            if(playing[40] && e.KeyCode==Keys.D1)
            {
                sunet.Stop();
                playing[40] = false;
            }
            else if(playing[1] && e.KeyCode==Keys.Q)
            {
                C1.Stop();
                playing[1] = false;
                button1.BackColor = System.Drawing.Color.White;
            }
            else if (playing[2] && e.KeyCode == Keys.D2)
            {
                C1S.Stop();
                playing[2] = false;
                button2.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[3] && e.KeyCode == Keys.W)
            {
                D1.Stop();
                playing[3] = false;
                button3.BackColor = System.Drawing.Color.White;
            }
            else if (playing[4] && e.KeyCode == Keys.D3)
            {
                D1S.Stop();
                playing[4] = false;
                button4.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[5] && e.KeyCode == Keys.E)
            {
                E1.Stop();
                playing[5] = false;
                button5.BackColor = System.Drawing.Color.White;
            }
            else if (playing[6] && e.KeyCode == Keys.R)
            {
                F1.Stop();
                playing[6] = false;
                button6.BackColor = System.Drawing.Color.White;
            }
            else if (playing[7] && e.KeyCode == Keys.D5)
            {
                F1S.Stop();
                playing[7] = false;
                button7.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[8] && e.KeyCode == Keys.T)
            {
                G1.Stop();
                playing[8] = false;
                button8.BackColor = System.Drawing.Color.White;
            }
            else if (playing[9] && e.KeyCode == Keys.D6)
            {
                G1S.Stop();
                playing[9] = false;
                button9.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[10] && e.KeyCode == Keys.Y)
            {
                A1.Stop();
                playing[10] = false;
                button10.BackColor = System.Drawing.Color.White;
            }
            else if (playing[11] && e.KeyCode == Keys.D7)
            {
                A1S.Stop();
                playing[11] = false;
                button11.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[12] && e.KeyCode == Keys.U)
            {
                B1.Stop();
                playing[12] = false;
                button12.BackColor = System.Drawing.Color.White;
            }
            else if (playing[13] && e.KeyCode == Keys.I)
            {
                C2.Stop();
                playing[13] = false;
                button13.BackColor = System.Drawing.Color.White;
            }
            else if (playing[14] && e.KeyCode == Keys.D9)
            {
                C2S.Stop();
                playing[14] = false;
                button14.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[15] && e.KeyCode == Keys.O)
            {
                D2.Stop();
                playing[15] = false;
                button15.BackColor = System.Drawing.Color.White;
            }
            else if (playing[16] && e.KeyCode == Keys.D0)
            {
                D2S.Stop();
                playing[16] = false;
                button16.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[17] && e.KeyCode == Keys.Z)
            {
                E2.Stop();
                playing[17] = false;
                button17.BackColor = System.Drawing.Color.White;
            }
            else if (playing[18] && e.KeyCode == Keys.X)
            {
                F2.Stop();
                playing[18] = false;
                button18.BackColor = System.Drawing.Color.White;
            }
            else if (playing[19] && e.KeyCode == Keys.D)
            {
                F2S.Stop();
                playing[19] = false;
                button19.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[20] && e.KeyCode == Keys.C)
            {
                G2.Stop();
                playing[20] = false;
                button20.BackColor = System.Drawing.Color.White;
            }
            else if (playing[21] && e.KeyCode == Keys.F)
            {
                G2S.Stop();
                playing[21] = false;
                button21.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[22] && e.KeyCode == Keys.V)
            {
                A2.Stop();
                playing[22] = false;
                button22.BackColor = System.Drawing.Color.White;
            }
            else if (playing[23] && e.KeyCode == Keys.G)
            {
                A2S.Stop();
                playing[23] = false;
                button23.BackColor = System.Drawing.Color.Gray;
            }
            else if (playing[24] && e.KeyCode == Keys.B)
            {
                B2.Stop();
                playing[24] = false;
                button24.BackColor = System.Drawing.Color.White;
            }
            else if (playing[25] && e.KeyCode == Keys.N)
            {
                C3.Stop();
                playing[25] = false;
                button25.BackColor = System.Drawing.Color.White;
            }
            label1.Text=label1.Text.Remove(0,label1.Text.Length);
        } 
        private async void timer1_Tick(object sender, EventArgs e)
        {
            for(int i=beg;i<=szi;i++)
            {
                
                imagini[i].Location = new Point(imagini[i].Location.X, imagini[i].Location.Y + 7);
                if (i == cnt && pushed[i] == false && imagini[i].Location.Y >= button1.Location.Y - imagini[i].Height / 2 - 10)
                    timer1.Stop();
                if (pushed[i] == true && timer1.Enabled == false)
                    timer1.Start();
                if (imagini[i].Location.Y >= button1.Location.Y - imagini[i].Height / 2)
                {
                    if (pushed[i])
                    {
                        if (timer1.Enabled == false)
                            timer1.Start();
                        beg++;
                        panel1.Controls.Remove(imagini[i]);
                        imagini[i].Visible = false;
                        imagini[i].Enabled = false;
                        
                    }
                    else
                    {
                        timer1.Stop();
                        scor -= 2;
                        label2.ForeColor = System.Drawing.Color.Red;
                        label2.Text = "Apasa nota!";
                    }
                }
                if (i == 1 && imagini[i].Location.Y >= (button1.Location.Y) && !motoare)
                {
                    motoare = true;
                    //sunet.Open(new Uri(@"E:\C#\Pian\Pian\bin\Debug\bogat.wav"));
                    //sunet.Play();
                }                
            }
        }
        private void Startbutton_Click(object sender, EventArgs e)
        {
            if(Startbutton.Text=="Start") //Start
            {
                timer1.Enabled = true;
                Startbutton.Text = "Stop";
                recording = false;
                button1.Select();
                ruleaza();
            }
            else  //Stop
            {
                recording = true;
                Startbutton.Text = "Start";
                timer1.Enabled = false;
                button1.Select();
                szi = 0;
                aux1 = 0;
                player2 = false;
                motoare = false;
                beg = 1;  
                sz = 0;
                cnt = 0;
                dar = 1;

                timer1.Enabled = false;
                button1.Select();
                sw = Stopwatch.StartNew();
                for (int i = 0; i < 20; i++)
                    playing[i] = false;
                poza1.Visible = false;
                d[0].timp = 0;
                motoare = false;
                cnt = 0;
                beg = 1;
                label2.ForeColor = System.Drawing.Color.LawnGreen;
                label2.Text = "Bine ai venit!";
                scor = 0;
                label_scor.Text = "0";
                for (int i = 0; i < 100; i++)
                    pushed[i] = false;
            }     
        }

        private void label2_Click(object sender, EventArgs e)
        {

        }

        private void button2_back_Click(object sender, EventArgs e)
        {
            
            this.Hide();
            Form2 f2 = new Form2();
            f2.Location = this.Location;
            f2.Width = this.Width;
            f2.Height = this.Height;
            f2.Show();
        }

        
    }
}
